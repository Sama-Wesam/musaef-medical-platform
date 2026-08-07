<?php

namespace App\Listeners;

use App\Events\DonationAccepted;
use App\Models\RewardTransaction;
use App\Notifications\RewardNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateRewardPoints implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * تنفيذ الحدث
     */
    public function handle(DonationAccepted $event): void
    {
        // منح المتبرع 50 نقطة لاستجابته لنداء الطوارئ
        $pointsEarned = 50;

        // تسجيل الحركة في قاعدة البيانات
        RewardTransaction::create([
            'donor_id' => $event->donor->id,
            'points' => $pointsEarned,
            'type' => 'earned',
            'description' => 'الاستجابة لنداء طوارئ رقم: ' . $event->bloodRequest->id,
        ]);

        // إرسال إشعار للمتبرع بالنقاط الجديدة
        $event->donor->user->notify(new RewardNotification(
            $pointsEarned, 
            'شكراً لبطولتك واستجابتك السريعة لنداء الطوارئ!'
        ));
    }
}