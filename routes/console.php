<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\BloodRequest;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ================== أوامر وجدولة منصة مسعف الأوتوماتيكية ==================

// 1. تنظيف طلبات الطوارئ منتهية الصلاحية (كل ساعة مع منع التداخل)
Schedule::command('emergencies:clean')
    ->hourly()
    ->withoutOverlapping()
    ->onOneServer();

// 2. إرسال التقارير اليومية لجميع المدراء صباحاً الساعة 06:00
Schedule::command('reports:send-daily')
    ->dailyAt('06:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily_reports.log'));

// 3. فحص مستويات مخزون الدم وإرسال التنبيهات (كل 6 ساعات)
Schedule::command('statistics:update-blood')
    ->everySixHours()
    ->withoutOverlapping();

// 4. تحديث التوقعات الذكية (AI Forecast) أسبوعياً يوم الأحد منتصف الليل
Artisan::command('ai:forecast', function () {
    $this->info('جاري تشغيل خوارزمية الذكاء الاصطناعي لاحتياج المستشفيات...');
    // AIService لتوليد التقرير الأسبوعي وتحليلات الخريطة الحرارية
    $this->info('تم التحديث بنجاح.');
})->purpose('تحديث توقعات الذكاء الاصطناعي لاحتياج المستشفيات');

Schedule::command('ai:forecast')
    ->weeklyOn(0, '00:00')
    ->withoutOverlapping();
