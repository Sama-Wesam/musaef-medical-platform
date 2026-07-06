<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Hospital;
use App\Enums\UserRole;

class HospitalPolicy
{
    /**
     * من يحق له تعديل بيانات المستشفى (العنوان، الموقع، الخ)؟
     */
    public function update(User $user, Hospital $hospital): bool
    {
        // الإدارة أو المستشفى نفسه
        return $user->role === UserRole::ADMIN || $hospital->user_id === $user->id;
    }

    /**
     * من يحق له إدارة وتعديل مخزون الدم الخاص بهذا المستشفى؟
     */
    public function manageInventory(User $user, Hospital $hospital): bool
    {
        // المستشفى نفسه فقط
        return $user->role === UserRole::HOSPITAL && $hospital->user_id === $user->id;
    }
    
    /**
     * من يحق له استعراض الاستجابات من المتبرعين الخاصة بهذا المستشفى؟
     */
    public function viewResponses(User $user, Hospital $hospital): bool
    {
        return $user->role === UserRole::ADMIN || $hospital->user_id === $user->id;
    }
}