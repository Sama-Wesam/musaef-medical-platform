<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // قم بتسجيل الخدمات (Services)، المستودعات (Repositories)،
        // أو أي ارتباطات (Bindings) بداخل حاوية التطبيق (Service Container) هنا.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. فرض رابط الأمان HTTPS عند رفع المشروع على سيرفر الإنتاج
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // 2. منع التحميل المتأخر (Lazy Loading) في البيئة المحلية للتأكد من أداء الاستعلامات العالي (N+1 Query Assurance)
        Model::preventLazyLoading(! $this->app->isProduction());

        // ضع هنا أي أكواد تحتاج للعمل عند بدء التطبيق
        // مثل مشاركة البيانات مع جميع الـ Views، أو تسجيل الـ Observers
        // أو إعداد المكونات الإضافية (Macros)
    }
}
