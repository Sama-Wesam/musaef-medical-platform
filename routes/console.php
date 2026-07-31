<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\BloodRequest;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ================== أوامر وجدولة منصة مسعف الأوتوماتيكية ==================

// 1. تنظيف طلبات الطوارئ المنتهية الصلاحية كل 6 ساعات
Schedule::command('emergencies:clean')->everySixHours();

// 2. إرسال التقارير اليومية لجميع المدراء كل يوم الساعة 8 صباحاً
Schedule::command('reports:send-daily')->dailyAt('08:00');

// 3. فحص مستويات مخزون الدم وإرسال التنبيهات للمستشفيات مرتين يومياً (الساعة 1 صباحاً و 1 ظهراً)
Schedule::command('statistics:update-blood')->twiceDaily(1, 13);

// 4. تحديث التوقعات الذكية (AI Forecast) كل أسبوع لتوقع احتياجات المستشفيات
Artisan::command('ai:forecast', function () {
    $this->info('جاري تشغيل خوارزمية الذكاء الاصطناعي لتوقع احتياج الدم...');
    // AIService لتوليد التقرير الأسبوعي
    $this->info('تم التحديث بنجاح.');
})->purpose('تحديث توقعات الذكاء الاصطناعي لاحتياج المستشفيات');

// جدولة أمر الذكاء الاصطناعي ليعمل كل يوم أحد منتصف الليل
Schedule::command('ai:forecast')->weeklyOn(0, '00:00');
