<?php

namespace App\Policies;

use App\Models\Donation;
use App\Models\User;

class DonationPolicy
{
    /**
     * منح مدير النظام صلاحيات كاملة تلقائياً
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * عرض تفاصيل التبرع للمتبرع المعني أو المستشفى المستقل
     */
    public function view(User $user, Donation $donation): bool
    {
        if ($user->isDonor() && $donation->donor?->user_id === $user->id) {
            return true;
        }

        if ($user->isHospital() && $donation->hospital?->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * تسجيل عملية تبرع جديدة (للمستشفيات الموثقة فقط)
     */
    public function create(User $user): bool
    {
        return $user->isHospital() && (bool) $user->hospital?->is_verified;
    }

    /**
     * تعديل بيانات التبرع
     */
    public function update(User $user, Donation $donation): bool
    {
        return $user->isHospital() && (bool) $user->hospital?->is_verified && $donation->hospital?->user_id === $user->id;
    }
}
