<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

// الأحداث (Events)
use App\Events\UserRegistered;
use App\Events\EmergencyCreated;
use App\Events\DonationAccepted;
use App\Events\DonationRejected;
use App\Events\EmergencyResolved;
use App\Events\EmergencyStatusUpdated;

// المستمعون (Listeners)
use App\Listeners\SendWelcomeNotification;
use App\Listeners\AssignDefaultBadges;
use App\Listeners\NotifyNearbyDonors;
use App\Listeners\SendEmergencyNotification;
use App\Listeners\UpdateDonationStats;
use App\Listeners\GenerateRewardPoints;

class EventServiceProvider extends ServiceProvider
{
    /**
     * جدول ربط الأحداث بالمستمعين (Event Listener Mappings)
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // 1. عند تسجيل مستخدم جديد
        UserRegistered::class => [
            SendWelcomeNotification::class,
            AssignDefaultBadges::class,
        ],

        // 2. عند إنشاء طلب طوارئ جديد
        EmergencyCreated::class => [
            NotifyNearbyDonors::class,
            SendEmergencyNotification::class,
        ],

        // 3. عند قبول التبرع من قبل متبرع
        DonationAccepted::class => [
            UpdateDonationStats::class,
            GenerateRewardPoints::class,
        ],

        // 4. عند رفض أو اعتذار المتبرع
        DonationRejected::class => [
            // مستمعون مستقبليون لإعادة التوجيه تلقائياً
        ],

        // 5. عند تحديث حالة الطوارئ
        EmergencyStatusUpdated::class => [
            // مستمعون لتحديث حالة الاستجابة
        ],

        // 6. عند اكتمال وحل حالة الطوارئ
        EmergencyResolved::class => [
            // مستمعون لأرشفة الحالات المنتهية
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
