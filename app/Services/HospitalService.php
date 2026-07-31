<?php

namespace App\Services;

use App\Repositories\HospitalRepository;
use App\Repositories\BloodInventoryRepository;

class HospitalService
{
    protected $hospitalRepo;
    protected $inventoryRepo;

    public function __construct(HospitalRepository $hospitalRepo, BloodInventoryRepository $inventoryRepo)
    {
        $this->hospitalRepo = $hospitalRepo;
        $this->inventoryRepo = $inventoryRepo;
    }

    public function getInventory(int $hospitalId)
    {
        return $this->inventoryRepo->getInventoryForHospital($hospitalId);
    }

    public function manualInventoryUpdate(int $hospitalId, int $bloodTypeId, int $units, string $operation)
    {
        return $this->inventoryRepo->updateInventory($hospitalId, $bloodTypeId, $units, $operation);
    }
}
