<?php

namespace App\Listeners;

use App\Events\EmergencyCreated;
use App\Jobs\ProcessEmergencyMatchingJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyNearbyDonors implements ShouldQueue
{
    use InteractsWithQueue;

    public int $tries = 3;

    /**
     * تنفيذ الحدث
     */
    public function handle(EmergencyCreated $event): void
    {
        // بمجرد إنشاء الطوارئ، نقوم بتحويل الطلب إلى الـ Job الخاص بالذكاء الاصطناعي في الخلفية
        ProcessEmergencyMatchingJob::dispatch($event->bloodRequest);
    }
}
