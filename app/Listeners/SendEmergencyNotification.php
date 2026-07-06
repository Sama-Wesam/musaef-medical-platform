<?php

namespace App\Listeners;

use App\Events\EmergencyCreated;
use App\Models\User;
use App\Notifications\NewRequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendEmergencyNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * تنفيذ الحدث
     */
    public function handle(EmergencyCreated $event): void
    {
        // جلب جميع مدراء النظام لإعلامهم بالطلب الجديد (للمراقبة أو لمنع الاحتيال)
        $admins = User::where('role', 'admin')->get();

        // إرسال الإشعار لجميع المدراء في الخلفية
        Notification::send($admins, new NewRequestNotification($event->bloodRequest));
    }
}