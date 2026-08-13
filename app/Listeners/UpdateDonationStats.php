<?php

namespace App\Listeners;

use App\Events\DonationAccepted;
use App\Models\DonorResponse;
use App\Models\BloodRequest;
use App\Enums\RequestStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateDonationStats implements ShouldQueue
{
    use InteractsWithQueue;

    public int $tries = 3;

    /**
     * تنفيذ الحدث مع معالجة الحالات المتزامنة (Race Conditions) باستخدام Pessimistic Locking والربط بـ Enums
     */
    public function handle(DonationAccepted $event): void
    {
        try {
            DB::transaction(function () use ($event) {
                $donor = $event->donor;

                // التعديل الجوهري: استخدام القفل الحقيقي للصف في قاعدة البيانات لتجنب التعارض أثناء الضغط المتزامن
                $bloodRequest = BloodRequest::where('id', $event->bloodRequest->id)
                    ->lockForUpdate()
                    ->first();

                // التحقق من وجود الطلب لتفادي أخطاء null
                if (!$bloodRequest) {
                    return;
                }

                $acceptedStatus = class_exists(RequestStatus::class) && defined('App\Enums\RequestStatus::ACCEPTED')
                    ? (RequestStatus::ACCEPTED->value ?? RequestStatus::ACCEPTED)
                    : 'accepted';

                $searchingStatus = class_exists(RequestStatus::class) && defined('App\Enums\RequestStatus::SEARCHING')
                    ? (RequestStatus::SEARCHING->value ?? RequestStatus::SEARCHING)
                    : 'searching';

                // 1. تسجيل استجابة المتبرع في قاعدة البيانات
                DonorResponse::updateOrCreate(
                    ['blood_request_id' => $bloodRequest->id, 'donor_id' => $donor->id],
                    ['status' => $acceptedStatus]
                );

                // 2. تحديث حالة المتبرع ليصبح "غير متاح" مؤقتاً
                $donor->update(['is_available' => false]);

                // 3. تحديث حالة الطلب إلى "تم القبول" إذا كان لا يزال يبحث
                $currentStatus = is_object($bloodRequest->status) && method_exists($bloodRequest->status, 'value')
                    ? $bloodRequest->status->value
                    : (string) $bloodRequest->status;

                if ($currentStatus === $searchingStatus) {
                    $bloodRequest->update(['status' => $acceptedStatus]);
                }
            });
        } catch (\Throwable $e) {
            Log::error("Error updating donation stats for BloodRequest ID {$event->bloodRequest->id}: {$e->getMessage()}");
        }
    }
}
