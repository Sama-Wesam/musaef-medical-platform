<?php

namespace App\Policies;

use App\Models\BloodRequest;
use App\Models\User;

class EmergencyPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * إنشاء طلب طوارئ جديد (للمستشفيات الموثقة فقط)
     */
    public function create(User $user): bool
    {
        return $user->isHospital() && (bool) $user->hospital?->is_verified;
    }

    public function update(User $user, BloodRequest $bloodRequest): bool
    {
        return $user->isHospital() && (bool) $user->hospital?->is_verified && $bloodRequest->hospital_id === $user->hospital?->id;
    }

    public function delete(User $user, BloodRequest $bloodRequest): bool
    {
        return $user->isHospital() && (bool) $user->hospital?->is_verified && $bloodRequest->hospital_id === $user->hospital?->id;
    }
}
