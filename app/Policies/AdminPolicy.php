<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRole;

class AdminPolicy
{
    public function accessDashboard(User $user): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::ADMIN->value;
    }

    public function manageUsers(User $user): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::ADMIN->value;
    }

    public function verifyHospitals(User $user): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::ADMIN->value;
    }
}
