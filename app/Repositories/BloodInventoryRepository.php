<?php

namespace App\Repositories;

use App\Models\BloodInventory;
use Illuminate\Database\Eloquent\Collection;

class BloodInventoryRepository
{
    public function getInventoryForHospital(int $hospitalId): Collection
    {
        return BloodInventory::with('bloodType')
            ->where('hospital_id', $hospitalId)
            ->get();
    }

    public function updateInventory(int $hospitalId, int $bloodTypeId, int $units, string $operation = 'add'): bool
    {
        $inventory = BloodInventory::firstOrCreate(
            ['hospital_id' => $hospitalId, 'blood_type_id' => $bloodTypeId],
            ['units_available' => 0]
        );

        if ($operation === 'add') {
            $inventory->units_available += $units;
        } elseif ($operation === 'sub' && $inventory->units_available >= $units) {
            $inventory->units_available -= $units;
        } else {
            return false; // لا يمكن خصم كمية أكبر من المتاح
        }

        $inventory->last_updated_at = now();
        return $inventory->save();
    }
}