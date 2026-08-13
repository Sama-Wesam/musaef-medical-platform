<?php

namespace App\Repositories;

use App\Models\BloodInventory;
use App\Repositories\Contracts\BloodInventoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BloodInventoryRepository implements BloodInventoryRepositoryInterface
{
    public function getInventoryForHospital(int $hospitalId): Collection
    {
        return BloodInventory::with('bloodType')
            ->where('hospital_id', $hospitalId)
            ->get();
    }

    /**
     * تحديث مخزون الدم باستخدام العمليات الحسابية الذرية
     */
    public function updateInventory(int $hospitalId, int $bloodTypeId, int $units, string $operation = 'add'): bool
    {
        $inventory = BloodInventory::firstOrCreate(
            ['hospital_id' => $hospitalId, 'blood_type_id' => $bloodTypeId],
            ['units_available' => 0]
        );

        if ($operation === 'add') {
            return (bool) $inventory->increment('units_available', $units, ['last_updated_at' => now()]);
        }

        if ($operation === 'sub' && $inventory->units_available >= $units) {
            return (bool) $inventory->decrement('units_available', $units, ['last_updated_at' => now()]);
        }

        return false;
    }
}
