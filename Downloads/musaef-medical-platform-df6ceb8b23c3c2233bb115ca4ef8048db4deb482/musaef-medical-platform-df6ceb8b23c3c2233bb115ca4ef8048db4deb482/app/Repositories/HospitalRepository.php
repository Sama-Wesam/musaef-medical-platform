<?php

namespace App\Repositories;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Collection;

class HospitalRepository
{
    public function getVerifiedHospitals(): Collection
    {
        return Hospital::with('user')
            ->where('is_verified', true)
            ->get();
    }

    public function findByUserId(int $userId): ?Hospital
    {
        return Hospital::where('user_id', $userId)->first();
    }

    public function createHospital(array $data): Hospital
    {
        return Hospital::create($data);
    }

    public function verifyHospital(int $hospitalId): bool
    {
        $hospital = Hospital::find($hospitalId);
        if ($hospital) {
            return $hospital->update(['is_verified' => true]);
        }
        return false;
    }
}