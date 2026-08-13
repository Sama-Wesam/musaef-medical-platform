<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\AI\SmartMatchingEngine;

class ProcessEmergencyMatchingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public BloodRequest $bloodRequest;

    /**
     * إعدادات إعادة المحاولة والمهلة الزمنية
     */
    public int $tries = 3;
    public array $backoff = [10, 30];
    public int $timeout = 60;

    public function __construct(BloodRequest $bloodRequest)
    {
        $this->bloodRequest = $bloodRequest;
        // توجيه مهمة المطابقة إلى قناة المعالجة السريعة
        $this->onQueue('high-priority');
    }

    public function handle(SmartMatchingEngine $matchingEngine): void
    {
        // تشغيل خوارزمية المطابقة (لجلب أفضل 20 متبرع)
        $topMatches = $matchingEngine->runMatching($this->bloodRequest, 20);

        // جلب كائنات المتبرعين بناءً على النتيجة
        $donorIds = array_column($topMatches, 'donor_id');

        if (!empty($donorIds)) {
            $sanitizedIds = array_map('intval', $donorIds);
            $implodedIds = implode(',', $sanitizedIds);

            // الحفاظ على ترتيب الأولوية القادم من الذكاء الاصطناعي مع تحميل علاقة الـ User
            $query = Donor::whereIn('id', $sanitizedIds)->with('user');

            // دعم التوافق مع قواعد بيانات أثناء إجراء اختبارات الوحدة Unit Tests
            if (config('database.default') === 'sqlite') {
                $donors = $query->get()->sortBy(function ($donor) use ($sanitizedIds) {
                    return array_search($donor->id, $sanitizedIds);
                });
            } else {
                $donors = $query->orderByRaw("FIELD(id, {$implodedIds})")->get();
            }

            // تمرير المتبرعين لجوب إرسال الإشعارات على نفس القناة السريعة
            if ($donors->isNotEmpty()) {
                SendBulkNotificationsJob::dispatch($donors, $this->bloodRequest)
                    ->onQueue('high-priority');
            }
        }
    }
}
