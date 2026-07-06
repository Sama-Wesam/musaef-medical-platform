<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRole;

class AdminPolicy
{
    /**
     * هل يحق للمستخدم الدخول إلى لوحة تحكم الإدارة؟
     */
    public function accessDashboard(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    /**
     * هل يحق للمستخدم إدارة المستخدمين الآخرين (حظر، تفعيل)؟
     */
    public function manageUsers(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    /**
     * هل يحق للمستخدم التحقق من المستشفيات والموافقة عليها؟
     */
    public function verifyHospitals(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}