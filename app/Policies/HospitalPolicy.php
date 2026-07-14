<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Hospital;
use App\Enums\UserRole;

class HospitalPolicy
{
    public function update(User $user, Hospital $hospital): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::ADMIN->value || $hospital->user_id === $user->id;
    }

    public function manageInventory(User $user, Hospital $hospital): bool
    {
        return ($user->role === UserRole::HOSPITAL || $user->role === UserRole::HOSPITAL->value) && $hospital->user_id === $user->id;
    }

    public function viewResponses(User $user, Hospital $hospital): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::ADMIN->value || $hospital->user_id === $user->id;
    }
}
