<?php

namespace App\Repositories\Contracts;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Collection;

interface HospitalRepositoryInterface
{
    public function getVerifiedHospitals(): Collection;
    public function findByUserId(int $userId): ?Hospital;
    public function createHospital(array $data): Hospital;
    public function verifyHospital(int $hospitalId): bool;
}
