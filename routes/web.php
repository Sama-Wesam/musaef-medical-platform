<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

// ⚡ مسار مؤقت لإعادة إنشاء كافة الجداول من الصفر وتنفيذ الـ Seeders وإزالة الكاش في Render
Route::get('/run-setup-musaef', function () {
    try {
        // إعادة بناء الهيكلية بالكامل من الصفر لحل تعارض الأعمدة المفقودة
        Artisan::call('migrate:fresh', ['--force' => true]);
        $migrateOutput = Artisan::output();

        // إدخال البيانات الأولية والاختبارية
        Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = Artisan::output();

        // تنظيف الكاش كلياً
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return response()->json([
            'status' => 'success',
            'message' => 'تم إعادة بناء الجداول بالكامل وإدخال البيانات وإزالة الكاش بنجاح!',
            'migrate' => trim($migrateOutput),
            'seed' => trim($seedOutput)
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'حدث خطأ أثناء تنفيذ التهيئة: ' . $e->getMessage()
        ], 500);
    }
});
