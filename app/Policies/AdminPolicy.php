<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class AdminPolicy
{
    /**
     * التحقق الشامل لوظائف الأدمن
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null; // السماح للفحص بالاستمرار بمرونة
    }

    public function accessDashboard(User $user): bool
    {
        return $user->isAdmin();
    }

    public function manageUsers(User $user): bool
    {
        return $user->isAdmin();
    }

    public function verifyHospitals(User $user): bool
    {
        return $user->isAdmin();
    }
}
