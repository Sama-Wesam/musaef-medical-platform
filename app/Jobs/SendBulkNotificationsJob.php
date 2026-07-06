<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\BloodRequest;
use App\Notifications\EmergencyNotification;

class SendBulkNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $donors;
    public $bloodRequest;

    /**
     * إنشاء الجوب (يستقبل المتبرعين والطلب)
     */
    public function __construct($donors, BloodRequest $bloodRequest)
    {
        $this->donors = $donors;
        $this->bloodRequest = $bloodRequest;
    }

    /**
     * تنفيذ المهمة
     */
    public function handle(): void
    {
        foreach ($this->donors as $donor) {
            // إرسال الإشعار عبر النظام
            $donor->user->notify(new EmergencyNotification($this->bloodRequest));

            // إرسال SMS إذا كانت الحالة حرجة (استخدام الـ Helper الذي صنعناه)
            if ($this->bloodRequest->emergency_level === 'critical' && $donor->user->phone) {
                $message = "طوارئ (مسعف): مستشفى {$this->bloodRequest->hospital->user->name} يحتاج فصيلة {$this->bloodRequest->bloodType->name} فوراً!";
                send_emergency_sms($donor->user->phone, $message);
            }
        }
    }
}