<?php

namespace App\Listeners;

use App\Events\EmergencyCreated;
use App\Models\User;
use App\Enums\UserRole;
use App\Notifications\NewRequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class SendEmergencyNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public int $tries = 3;

    /**
     * تنفيذ الحدث
     */
    public function handle(EmergencyCreated $event): void
    {
        try {
            // جلب قيمة رتبة الأدمن سواء كانت Enum أو String
            $adminRole = class_exists(UserRole::class) && defined('App\Enums\UserRole::ADMIN')
                ? (UserRole::ADMIN->value ?? UserRole::ADMIN)
                : 'admin';

            $admins = User::where('role', $adminRole)->get();

            // إرسال الإشعار لجميع المدراء في الخلفية
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewRequestNotification($event->bloodRequest));
            }
        } catch (\Throwable $e) {
            Log::error("Failed to notify admins of Emergency ID {$event->bloodRequest->id}: {$e->getMessage()}");
        }
    }
}
