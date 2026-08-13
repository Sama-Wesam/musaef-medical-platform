<?php

namespace App\Repositories\Contracts;

use App\Models\BloodRequest;
use Illuminate\Database\Eloquent\Collection;

interface EmergencyRepositoryInterface
{
    public function getActiveEmergencies(): Collection;
    public function getRequestsByHospital(int $hospitalId): Collection;
    public function findById(int $id): ?BloodRequest;
    public function createRequest(array $data): BloodRequest;
    public function updateStatus(int $id, string $status): bool;
}
