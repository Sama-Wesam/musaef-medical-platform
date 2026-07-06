<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BloodRequest;
use App\Enums\UserRole;

class EmergencyPolicy
{
    /**
     * من يمكنه رؤية طلبات الدم والطوارئ؟
     */
    public function viewAny(User $user): bool
    {
        // الجميع يمكنهم رؤية الطلبات (حتى المتبرعين ليتمكنوا من الاستجابة)
        return true;
    }

    /**
     * من يحق له إطلاق نداء طوارئ أو إنشاء طلب توفير دم؟
     */
    public function create(User $user): bool
    {
        // فقط المستشفيات (الموثقة) يحق لها طلب الدم
        return $user->role === UserRole::HOSPITAL && $user->hospital && $user->hospital->is_verified;
    }

    /**
     * من يحق له تعديل الطلب (مثال: تغيير الحالة إلى مكتمل)؟
     */
    public function update(User $user, BloodRequest $bloodRequest): bool
    {
        // الإدارة، أو المستشفى الذي أنشأ الطلب فقط
        return $user->role === UserRole::ADMIN || 
               ($user->role === UserRole::HOSPITAL && $bloodRequest->hospital_id === $user->hospital->id);
    }

    /**
     * من يحق له إلغاء وحذف الطلب؟
     */
    public function delete(User $user, BloodRequest $bloodRequest): bool
    {
        return $user->role === UserRole::ADMIN || 
               ($user->role === UserRole::HOSPITAL && $bloodRequest->hospital_id === $user->hospital->id);
    }
}