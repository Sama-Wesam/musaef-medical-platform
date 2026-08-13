<?php

namespace App\Services;

use App\Models\BloodType;
use App\Models\BloodInventory;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;

class HospitalService
{
    public function getInventoryData(int $hospitalId): array
    {
        $bloodTypes = BloodType::all();

        $inventories = BloodInventory::where('hospital_id', $hospitalId)
            ->get()
            ->keyBy('blood_type_id');

        $formattedInventory = [];
        $totalUnits = 0;
        $validUnits = 0;
        $lowStockUnits = 0;
        $criticalTypesCount = 0;
        $urgentAlerts = [];

        foreach ($bloodTypes as $type) {
            $inv = $inventories->get($type->id);
            $available = $inv ? $inv->units_available : 0;
            $minRequired = $inv ? ($inv->min_limit ?? 10) : 10;

            $percentage = $minRequired > 0 ? round(($available / $minRequired) * 100) : 100;

            $status = 'طبيعي';
            if ($available == 0 || $percentage < 30) {
                $status = 'حرج';
                $criticalTypesCount++;
                $urgentAlerts[] = [
                    'blood_type'     => $type->name,
                    'status'         => 'critical',
                    'available_text' => "متوفر {$available} وحدات فقط",
                ];
            } elseif ($percentage < 70) {
                $status = 'منخفض';
                $lowStockUnits += $available;
                $urgentAlerts[] = [
                    'blood_type'     => $type->name,
                    'status'         => 'low',
                    'available_text' => "متوفر {$available} وحدات",
                ];
            } else {
                $validUnits += $available;
            }

            $totalUnits += $available;

            $formattedInventory[] = [
                'id'            => $type->id,
                'blood_type_id' => $type->id,
                'type'          => $type->name,
                'available'     => $available,
                'minRequired'   => $minRequired,
                'statusRaw'     => $status,
                'percentage'    => $percentage,
            ];
        }

        $recentDonations = Donation::where('hospital_id', $hospitalId)
            ->with(['donor.user', 'bloodType'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($donation) {
                return [
                    'id'             => $donation->id,
                    'donor_name'     => $donation->donor?->user?->name ?? 'متبرع متطوع',
                    'blood_type'     => $donation->bloodType?->name ?? 'غير محدد',
                    'formatted_time' => $donation->created_at ? $donation->created_at->diffForHumans() : 'مؤخراً',
                ];
            });

        return [
            'stats' => [
                'totalUnits'         => $totalUnits,
                'validUnits'         => $validUnits,
                'lowStockUnits'      => $lowStockUnits,
                'criticalTypesCount' => $criticalTypesCount,
            ],
            'inventory'       => $formattedInventory,
            'urgentAlerts'    => $urgentAlerts,
            'recentDonations' => $recentDonations,
        ];
    }

    public function manualInventoryUpdate(int $hospitalId, int $bloodTypeId, int $units, string $operation, ?string $notes = null): bool
    {
        return DB::transaction(function () use ($hospitalId, $bloodTypeId, $units, $operation) {
            $inventory = BloodInventory::firstOrCreate(
                ['hospital_id' => $hospitalId, 'blood_type_id' => $bloodTypeId],
                ['units_available' => 0]
            );

            if ($operation === 'add') {
                $inventory->units_available += $units;
            } elseif ($operation === 'sub') {
                if ($inventory->units_available < $units) {
                    return false;
                }
                $inventory->units_available -= $units;
            }

            return (bool) $inventory->save();
        });
    }
}
