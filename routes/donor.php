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
use App\Http\Controllers\API\DonationController;

Route::prefix('donor')->group(function () {

    // ⚡ مسار الـ Polling لتحديث نداءات الطوارئ العاجلة والإشعارات للمتبرع
    Route::get('/polling/live-alerts', [DashboardController::class, 'livePollingAlerts']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/requests', [EmergencyNotificationsController::class, 'index']);

    // طلبات الطوارئ العاجلة المطلوبة في Dashboard.vue
    Route::get('/urgent-requests', [EmergencyNotificationsController::class, 'index']);

    Route::get('/ai-recommendations', [EmergencyNotificationsController::class, 'aiRecommendations']);

    // توحيد متحكم قبول الطلب لحل ثغرة تضارب النقاط والأوسمة
    Route::post('/requests/{id}/accept', [DonationController::class, 'acceptEmergency']);

    // 1. الملف الشخصي
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::match(['post', 'put'], '/profile', [ProfileController::class, 'update']);
    Route::match(['post', 'put'], '/profile/update', [ProfileController::class, 'update']);

    // 2. الاستبيان الصحي
    Route::post('/profile/health', [ProfileController::class, 'updateHealthQuestionnaire']);
    Route::post('/health-questionnaire', [ProfileController::class, 'updateHealthQuestionnaire']);

    // سجل التبرعات
    Route::get('/history', [DonationHistoryController::class, 'index']);

    // نداءات الطوارئ
    Route::get('/emergencies', [EmergencyNotificationsController::class, 'index']);
    Route::post('/emergencies/{id}/respond', [EmergencyNotificationsController::class, 'update']);

    // خدمات الموقع والحملات القريبة والبطاقة
    Route::get('/campaigns/nearby', [NearbyHospitalsController::class, 'index']);
    Route::post('/nearby-hospitals', [NearbyHospitalsController::class, 'index']);
    Route::get('/qr-card', [QRCardController::class, 'show']);

    // النقاط والإشعارات
    Route::get('/rewards', [RewardsController::class, 'index']);
    Route::get('/notifications', [NotificationsController::class, 'index']);
    Route::post('/notifications/read-all', [NotificationsController::class, 'markAllAsRead']);
    Route::post('/notifications/mark-as-read', [NotificationsController::class, 'markAllAsRead']);
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'update']);
});
