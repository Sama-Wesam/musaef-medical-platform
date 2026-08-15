<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BloodRequestController;
use App\Http\Controllers\API\DonationController;
use App\Http\Controllers\API\PublicController;
use App\Http\Controllers\Donor\DonationCenterController;
use App\Http\Controllers\Donor\DonationHistoryController;

/*
|--------------------------------------------------------------------------
| API Routes - منصة مسعف (محدثة لمنع التضارب وضمان حماية المسارات)
|--------------------------------------------------------------------------
*/

// ⚡ مسار الجذر للـ API لمنع خطأ 404 عند فتح /api وإرجاع حالة السيرفر
Route::get('/', function () {
    return response()->json([
        'status' => 'online',
        'message' => 'Musaef Medical Platform API is running successfully',
        'version' => '1.0.0'
    ], 200);
});

// ⚡ مسار تهيئة البيانات وقواعد البيانات عبر الـ API مباشرة
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
            'message' => 'تم تنفيذ الجداول والبيانات وإزالة الكاش بنجاح عبر الـ API!',
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

// 1. المسارات العامة (Public Routes)
Route::prefix('public')->group(function () {
    Route::get('/home-stats', [PublicController::class, 'getHomeStats']);
    Route::get('/urgent-requests', [PublicController::class, 'getUrgentRequests']);
    Route::get('/partners', [PublicController::class, 'getPartnersHospitals']);
    Route::post('/contact', [PublicController::class, 'sendContactMessage']);
    Route::get('/nearby-facilities', [PublicController::class, 'getNearbyFacilities']);

    // ⚡ مسار الـ Polling السريع للزوار/الصفحة الرئيسية
    Route::get('/polling/stats', [PublicController::class, 'getPollingStats']);
});

// 2. مسارات المصادقة العامة (Guest Auth Routes)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/register/donor', [AuthController::class, 'registerDonor']);
Route::post('/register/hospital', [AuthController::class, 'registerHospital']);

// مسارات تسجيل الدخول الاجتماعي عبر الشبكات (Google, Facebook, Apple)
Route::get('/auth/social/{provider}/redirect', [AuthController::class, 'redirectToProvider']);
Route::get('/auth/social/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

// 3. المسارات المحمية بواسطة Sanctum Token
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // استعلام الطوارئ والتبرعات داخل النطاق المحمي (حماية من الوصول غير المصرح به)
    Route::get('/emergencies/active', [BloodRequestController::class, 'index']);
    Route::get('/history', [DonationHistoryController::class, 'index']);
    Route::get('/rewards-and-card', [DonationHistoryController::class, 'index']);

    // توحيد مسار قبول الطلب واستكمال التبرع
    Route::post('/emergencies/{requestId}/accept', [DonationController::class, 'acceptEmergency']);
    Route::post('/donations/confirm', [DonationController::class, 'store']);

    Route::prefix('donation-center')->group(function () {
        Route::get('/ai-recommendations', [DonationCenterController::class, 'getAiRecommendations']);
        Route::get('/heatmap', [DonationCenterController::class, 'getHeatMapData']);
        Route::get('/requests', [DonationCenterController::class, 'getAllRequests']);
    });

    // استدعاء ملفات المسارات الفرعية للأدوار المختلفة
    if (file_exists(__DIR__.'/donor.php')) {
        require __DIR__.'/donor.php';
    }
    if (file_exists(__DIR__.'/hospital.php')) {
        require __DIR__.'/hospital.php';
    }
    if (file_exists(__DIR__.'/admin.php')) {
        require __DIR__.'/admin.php';
    }
});
