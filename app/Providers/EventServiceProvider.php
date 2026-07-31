<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\UserRegistered;
use App\Events\EmergencyCreated;
use App\Events\DonationAccepted;
use App\Events\EmergencyResolved;
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
        UserRegistered::class => [
            SendWelcomeNotification::class,
            AssignDefaultBadges::class,
        ],
        EmergencyCreated::class => [
            NotifyNearbyDonors::class,
            SendEmergencyNotification::class,
        ],
        DonationAccepted::class => [
            UpdateDonationStats::class,
            GenerateRewardPoints::class,
        ],
        EmergencyResolved::class => [
            // إضافة مستمعين لأرشفة الحالات المنتهية إن لزم
        ],
    ];

    /**
     * تسجيل أي خدمات للأحداث
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * تحديد ما إذا كان يجب اكتشاف الأحداث تلقائياً
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
