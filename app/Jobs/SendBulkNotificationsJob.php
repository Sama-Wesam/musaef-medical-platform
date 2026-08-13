<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\BloodRequest;
use App\Notifications\EmergencyNotification;
use Illuminate\Support\Facades\Log;

class SendBulkNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $donors;
    public BloodRequest $bloodRequest;

    /**
     * إعدادات إعادة المحاولة والمهلة الزمنية
     */
    public int $tries = 3;
    public array $backoff = [10, 30];
    public int $timeout = 60;

    public function __construct($donors, BloodRequest $bloodRequest)
    {
        $this->donors = $donors;
        $this->bloodRequest = $bloodRequest->loadMissing(['hospital.user', 'bloodType']);
        // تخصيص قناة الإرسال ذات الأولوية العالية
        $this->onQueue('high-priority');
    }

    /**
     * تنفيذ مهمة الإرسال الجماعي
     */
    public function handle(): void
    {
        $emergencyLevel = is_object($this->bloodRequest->emergency_level) && method_exists($this->bloodRequest->emergency_level, 'value')
            ? strtolower($this->bloodRequest->emergency_level->value)
            : strtolower((string) $this->bloodRequest->emergency_level);

        $hospitalName = $this->bloodRequest->hospital->facility_name ?? $this->bloodRequest->hospital->user->name ?? 'مستشفى';
        $bloodTypeName = $this->bloodRequest->bloodType->name ?? $this->bloodRequest->blood_type ?? '';

        foreach ($this->donors as $donor) {
            try {
                if (!$donor->user) {
                    continue;
                }

                // 1. إرسال الإشعار الداخلي للنظام
                $donor->user->notify(new EmergencyNotification($this->bloodRequest));

                // 2. إرسال رسالة SMS فورية إذا كانت الحالة حرجة ومتوفر رقم الهاتف
                if (in_array($emergencyLevel, ['critical', 'حرج', 'حرجة جداً']) && !empty($donor->user->phone)) {
                    $message = "طوارئ (مسعف): مستشفى {$hospitalName} يحتاج فصيلة {$bloodTypeName} فوراً!";
                    if (function_exists('send_emergency_sms')) {
                        send_emergency_sms($donor->user->phone, $message);
                    }
                }
            } catch (\Throwable $e) {
                Log::error("Failed to notify donor ID {$donor->id}: {$e->getMessage()}");
            }
        }
    }
}
