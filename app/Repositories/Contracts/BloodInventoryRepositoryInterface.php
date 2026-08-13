<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface BloodInventoryRepositoryInterface
{
    public function getInventoryForHospital(int $hospitalId): Collection;
    public function updateInventory(int $hospitalId, int $bloodTypeId, int $units, string $operation = 'add'): bool;
}
