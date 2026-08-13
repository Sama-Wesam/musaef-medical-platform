<?php

namespace App\Providers;

use App\AI\SmartMatchingEngine;
use App\Models\BloodRequest;
use App\Observers\BloodRequestObserver;
use App\Repositories\AIRepository;
use App\Repositories\BloodInventoryRepository;
use App\Repositories\Contracts\AIRepositoryInterface;
use App\Repositories\Contracts\BloodInventoryRepositoryInterface;
use App\Repositories\Contracts\DonationRepositoryInterface;
use App\Repositories\Contracts\EmergencyRepositoryInterface;
use App\Repositories\Contracts\HospitalRepositoryInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\DonationRepository;
use App\Repositories\EmergencyRepository;
use App\Repositories\HospitalRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * تسجيل الخدمات والعقود داخل حاوية التطبيق (Service Container)
     */
    public function register(): void
    {
        // 1. ربط محرك المطابقة الذكي كـ Singleton بأسلوب مبسط
        $this->app->singleton(SmartMatchingEngine::class, fn () => new SmartMatchingEngine());

        // 2. ربط واجهات المستودعات بالتنفيذات المباشرة (Repository Bindings)
        $this->app->bind(EmergencyRepositoryInterface::class, EmergencyRepository::class);
        $this->app->bind(BloodInventoryRepositoryInterface::class, BloodInventoryRepository::class);
        $this->app->bind(AIRepositoryInterface::class, AIRepository::class);
        $this->app->bind(DonationRepositoryInterface::class, DonationRepository::class);
        $this->app->bind(HospitalRepositoryInterface::class, HospitalRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * التهيئة الأولية لخدمات التطبيق عند البدء (Bootstrap)
     */
    public function boot(): void
    {
        // 1. فرض بروتوكول الأمان HTTPS في بيئة الإنتاج
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // 2. منع التحميل المتأخر (Lazy Loading) لضمان حماية الاستعلامات من مشكلة N+1
        Model::preventLazyLoading(! $this->app->isProduction());

        // 3. تسجيل الـ Observers
        if (class_exists(BloodRequestObserver::class)) {
            BloodRequest::observe(BloodRequestObserver::class);
        }
    }
}
