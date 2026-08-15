<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

// ⚡ مسار مؤقت آمن لتنفيذ التهيئة (Migrations, Seeders, Clear Cache) بدون الحاجة للـ Shell في Render
Route::get('/run-setup-musaef', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = Artisan::output();

        Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = Artisan::output();

        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return response()->json([
            'status' => 'success',
            'message' => 'تم تنفيذ الجداول والبيانات وإزالة الكاش بنجاح!',
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
