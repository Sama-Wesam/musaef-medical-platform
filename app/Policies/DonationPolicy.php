<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Donation;
use App\Enums\UserRole;

class DonationPolicy
{
    /**
     * من يمكنه رؤية عملية تبرع معينة؟
     */
    public function view(User $user, Donation $donation): bool
    {
        // الإدارة ترى كل شيء
        if ($user->role === UserRole::ADMIN) return true;
        
        // المتبرع يرى تبرعاته فقط
        if ($user->role === UserRole::DONOR && $donation->donor->user_id === $user->id) return true;
        
        // المستشفى يرى التبرعات التي تمت لديه فقط
        if ($user->role === UserRole::HOSPITAL && $donation->hospital->user_id === $user->id) return true;

        return false;
    }

    /**
     * من يحق له تسجيل عملية تبرع جديدة وتأكيدها؟
     */
    public function create(User $user): bool
    {
        // المستشفيات فقط هي من تقوم بتسجيل التبرع بعد سحب الدم
        return $user->role === UserRole::HOSPITAL;
    }

    /**
     * من يحق له تعديل حالة التبرع (مثال: من ناجح إلى فاشل)؟
     */
    public function update(User $user, Donation $donation): bool
    {
        // المستشفى الذي تم التبرع فيه، أو مدير النظام
        return $user->role === UserRole::ADMIN || 
               ($user->role === UserRole::HOSPITAL && $donation->hospital->user_id === $user->id);
    }
}