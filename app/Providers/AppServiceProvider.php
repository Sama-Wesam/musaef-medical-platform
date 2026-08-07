<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // ضع هنا أي أكواد تحتاج للعمل عند بدء التطبيق، 
        // مثل مشاركة البيانات مع جميع الـ Views، أو تسجيل الـ Observers، 
        // أو إعداد المكونات الإضافية (Macros).
    }
}