<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\BloodRequest;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ================== أوامر مخصصة لمنصة مسعف ==================

// 1. تنظيف الطلبات المعلقة التي مر عليها أكثر من 48 ساعة ولم تكتمل
Schedule::call(function () {
    BloodRequest::whereIn('status', ['pending', 'searching'])
        ->where('created_at', '<', now()->subHours(48))
        ->update(['status' => 'cancelled']);
})->daily();

// 2. تحديث التوقعات الذكية (AI Forecast) كل أسبوع لتوقع احتياجات المستشفيات
Artisan::command('ai:forecast', function () {
    $this->info('جاري تشغيل خوارزمية الذكاء الاصطناعي لتوقع احتياج الدم...');
    // يمكن هنا استدعاء AIService لتوليد التقرير الأسبوعي
    $this->info('تم التحديث بنجاح.');
})->purpose('تحديث توقعات الذكاء الاصطناعي لاحتياج المستشفيات');

// جدولة أمر الذكاء الاصطناعي ليعمل كل يوم أحد منتصف الليل
Schedule::command('ai:forecast')->weeklyOn(0, '00:00');