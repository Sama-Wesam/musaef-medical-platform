<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Donation;
use App\Enums\UserRole;

class DonationPolicy
{
    public function view(User $user, Donation $donation): bool
    {
        if ($user->role === UserRole::ADMIN || $user->role === UserRole::ADMIN->value) return true;

        if (($user->role === UserRole::DONOR || $user->role === UserRole::DONOR->value) && $donation->donor?->user_id === $user->id) return true;

        if (($user->role === UserRole::HOSPITAL || $user->role === UserRole::HOSPITAL->value) && $donation->hospital?->user_id === $user->id) return true;

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::HOSPITAL || $user->role === UserRole::HOSPITAL->value;
    }

    public function update(User $user, Donation $donation): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::ADMIN->value ||
               (($user->role === UserRole::HOSPITAL || $user->role === UserRole::HOSPITAL->value) && $donation->hospital?->user_id === $user->id);
    }
}
