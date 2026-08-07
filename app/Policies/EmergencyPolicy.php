<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BloodRequest;
use App\Enums\UserRole;

class EmergencyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return ($user->role === UserRole::HOSPITAL || $user->role === UserRole::HOSPITAL->value) && $user->hospital?->is_verified;
    }

    public function update(User $user, BloodRequest $bloodRequest): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::ADMIN->value ||
               (($user->role === UserRole::HOSPITAL || $user->role === UserRole::HOSPITAL->value) && $bloodRequest->hospital_id === $user->hospital?->id);
    }

    public function delete(User $user, BloodRequest $bloodRequest): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::ADMIN->value ||
               (($user->role === UserRole::HOSPITAL || $user->role === UserRole::HOSPITAL->value) && $bloodRequest->hospital_id === $user->hospital?->id);
    }
}
