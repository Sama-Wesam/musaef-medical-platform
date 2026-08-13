<?php

namespace App\Policies;

use App\Models\Hospital;
use App\Models\User;

class HospitalPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function update(User $user, Hospital $hospital): bool
    {
        return $user->isHospital() && $hospital->user_id === $user->id;
    }

    /**
     * إدارة مخزون الدم (تتطلب أن يكون المستشفى صاحب الحساب وموثقاً)
     */
    public function manageInventory(User $user, Hospital $hospital): bool
    {
        return $user->isHospital()
            && $hospital->user_id === $user->id
            && (bool) $hospital->is_verified;
    }

    /**
     * عرض استجابات المتبرعين
     */
    public function viewResponses(User $user, Hospital $hospital): bool
    {
        return $user->isHospital() && $hospital->user_id === $user->id;
    }
}
