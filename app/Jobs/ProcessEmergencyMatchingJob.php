<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\BloodRequest;
use App\AI\SmartMatchingEngine;

class ProcessEmergencyMatchingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $bloodRequest;

    public function __construct(BloodRequest $bloodRequest)
    {
        $this->bloodRequest = $bloodRequest;
    }

    public function handle(SmartMatchingEngine $matchingEngine): void
    {
        // تشغيل خوارزمية المطابقة (لجلب أفضل 20 متبرع مثلاً)
        $topMatches = $matchingEngine->runMatching($this->bloodRequest, 20);

        // جلب كائنات المتبرعين بناءً على النتيجة
        $donorIds = array_column($topMatches, 'donor_id');
        $donors = \App\Models\Donor::whereIn('id', $donorIds)->get();

        // تمرير المتبرعين لجوب إرسال الإشعارات
        if ($donors->count() > 0) {
            SendBulkNotificationsJob::dispatch($donors, $this->bloodRequest);
        }
    }
}