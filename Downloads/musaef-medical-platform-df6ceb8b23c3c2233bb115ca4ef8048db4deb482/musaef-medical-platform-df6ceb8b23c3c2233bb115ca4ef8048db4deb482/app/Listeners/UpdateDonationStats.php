<?php

namespace App\Listeners;

use App\Events\DonationAccepted;
use App\Models\DonorResponse;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateDonationStats implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * تنفيذ الحدث
     */
    public function handle(DonationAccepted $event): void
    {
        $donor = $event->donor;
        $bloodRequest = $event->bloodRequest;

        // 1. تسجيل استجابة المتبرع في قاعدة البيانات
        DonorResponse::updateOrCreate(
            ['blood_request_id' => $bloodRequest->id, 'donor_id' => $donor->id],
            ['status' => 'accepted']
        );

        // 2. تحديث حالة المتبرع ليصبح "غير متاح" مؤقتاً (Busy)
        $donor->update(['is_available' => false]);

        // 3. تحديث حالة الطلب إلى "تم القبول" إذا كان لا يزال يبحث
        if ($bloodRequest->status === 'searching') {
            $bloodRequest->update(['status' => 'accepted']);
        }
    }
}