<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Donor\DashboardController;
use App\Http\Controllers\Donor\ProfileController;
use App\Http\Controllers\Donor\DonationHistoryController;
use App\Http\Controllers\Donor\EmergencyNotificationsController;
use App\Http\Controllers\Donor\NearbyHospitalsController;
use App\Http\Controllers\Donor\RewardsController;
use App\Http\Controllers\Donor\QRCardController;
use App\Http\Controllers\Donor\NotificationsController;

Route::middleware(['auth:sanctum', 'donor'])->prefix('donor')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/requests', [EmergencyNotificationsController::class, 'index']); // مسار عرض جميع الطلبات
    Route::get('/ai-recommendations', [EmergencyNotificationsController::class, 'aiRecommendations']); // مسار التوصيات
    Route::post('/requests/{id}/accept', [EmergencyNotificationsController::class, 'accept']); // مسار قبول الطلب

    // الملف الشخصي والصحي
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/health', [ProfileController::class, 'updateHealthInfo']);

    // سجل التبرعات
    Route::get('/history', [DonationHistoryController::class, 'index']);

    // نداءات الطوارئ الخاصة بالمتبرع
    Route::get('/emergencies', [EmergencyNotificationsController::class, 'index']);
    Route::post('/emergencies/{id}/respond', [EmergencyNotificationsController::class, 'update']);

    // خدمات الموقع والبطاقة
    Route::post('/nearby-hospitals', [NearbyHospitalsController::class, 'index']);
    Route::get('/qr-card', [QRCardController::class, 'show']);

    // النقاط والإشعارات
    Route::get('/rewards', [RewardsController::class, 'index']);
    Route::get('/notifications', [NotificationsController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'update']);
});
