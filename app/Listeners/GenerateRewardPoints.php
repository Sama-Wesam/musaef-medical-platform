<?php

namespace App\Listeners;

use App\Events\DonationAccepted;
use App\Models\RewardTransaction;
use App\Notifications\RewardNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateRewardPoints implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * عدد مرات إعادة المحاولة في حالة الفشل
     */
    public int $tries = 3;

    /**
     * تنفيذ الحدث
     */
    public function handle(DonationAccepted $event): void
    {
        try {
            DB::transaction(function () use ($event) {
                $donor = $event->donor;
                $bloodRequest = $event->bloodRequest;

                // منح المتبرع 50 نقطة لاستجابته لنداء الطوارئ
                $pointsEarned = 50;

                // 1. تسجيل الحركة في سجل المعاملات
                RewardTransaction::create([
                    'donor_id'    => $donor->id,
                    'points'      => $pointsEarned,
                    'type'        => 'earned',
                    'description' => 'الاستجابة لنداء طوارئ رقم: ' . $bloodRequest->id,
                ]);

                // 2. تحديث إجمالي نقاط المتبرع في حقل رصيده المباشر
                if (method_exists($donor, 'increment')) {
                    $donor->increment('points', $pointsEarned);
                }

                // 3. إشعار المتبرع بالنقاط المكتسبة
                if ($donor->user) {
                    $donor->user->notify(new RewardNotification(
                        $pointsEarned,
                        'شكراً لبطولتك واستجابتك السريعة لنداء الطوارئ!'
                    ));
                }
            });
        } catch (\Throwable $e) {
            Log::error("Failed to generate reward points for Donor ID {$event->donor->id}: {$e->getMessage()}");
        }
    }
}
