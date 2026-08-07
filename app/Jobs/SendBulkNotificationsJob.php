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
     * إنشاء الجوب (يستقبل المتبرعين المستهدفين والطلب)
     */
    public function __construct($donors, BloodRequest $bloodRequest)
    {
        $this->donors = $donors;
        $this->bloodRequest = $bloodRequest;
    }

    /**
     * تنفيذ مهمة الإرسال الجماعي مع معالجة حالة الأحرف لمستوى الخطورة
     */
    public function handle(): void
    {
        foreach ($this->donors as $donor) {
            // 1. إرسال الإشعار الافتراضي عبر لوحة تحكم النظام
            $donor->user->notify(new EmergencyNotification($this->bloodRequest));

            // 2. تحويل النص إلى حروف صغيرة (strtolower) لضمان قبول المدخلات بجميع حالاتها (CRITICAL, Critical, critical)
            $emergencyLevel = strtolower($this->bloodRequest->emergency_level);

            // 3. إرسال رسالة SMS فورية إذا كانت الحالة حرجة ومتوفر رقم الهاتف
            if ($emergencyLevel === 'critical' && $donor->user->phone) {
                $message = "طوارئ (مسعف): مستشفى {$this->bloodRequest->hospital->user->name} يحتاج فصيلة {$this->bloodRequest->bloodType->name} فوراً!";
                send_emergency_sms($donor->user->phone, $message);
            }
        }
    }
}
