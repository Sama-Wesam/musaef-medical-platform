<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonorManagementController;
use App\Http\Controllers\Admin\HospitalManagementController;
use App\Http\Controllers\Admin\RequestManagementController;
use App\Http\Controllers\Admin\FraudDetectionController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\RewardsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\EmergencyRadarController;
use App\Http\Controllers\Admin\AccountManagementController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\NotificationController;

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    // ⚡ مسار الـ Polling الفوري للوحة الإدارة (مع توجيه صحيح للدالة داخل DashboardController)
    Route::get('/polling/live-feed', [DashboardController::class, 'livePollingFeed']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // مسارات الإشعارات
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    // مسارات رادار الطوارئ المباشر
    Route::get('/emergency-radar', [EmergencyRadarController::class, 'index']);
    Route::post('/emergency-radar/{id}/trigger-response', [EmergencyRadarController::class, 'triggerResponse']);

    // مركز التحليلات الذكية والتقارير
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    Route::get('/analytics/heatmap', [AnalyticsController::class, 'heatMapData']);
    Route::get('/analytics/heat-map', [AnalyticsController::class, 'heatMapData']);
    Route::get('/analytics/all-alerts', [AnalyticsController::class, 'allAlerts']);
    Route::get('/analytics/alerts', [AnalyticsController::class, 'allAlerts']);

    // مسار أداء جميع المستشفيات
    Route::get('/analytics/all-hospitals-performance', [AnalyticsController::class, 'allHospitalsPerformance']);
    Route::get('/analytics/hospitals-performance', [AnalyticsController::class, 'allHospitalsPerformance']);

    // مسارات التنبؤ بالطلب بالذكاء الاصطناعي
    Route::match(['get', 'post'], '/analytics/forecast', [AnalyticsController::class, 'demandForecast']);
    Route::match(['get', 'post'], '/analytics/demand-forecast', [AnalyticsController::class, 'demandForecast']);
    Route::match(['get', 'post'], '/demand-forecast', [AnalyticsController::class, 'demandForecast']);
    Route::get('/reports', [AnalyticsController::class, 'index']);

    // مسارات إدارة الحسابات والتعيينات الشاملة
    Route::get('/users', [AccountManagementController::class, 'getDonors']);
    Route::get('/accounts/donors', [AccountManagementController::class, 'getDonors']);
    Route::get('/accounts/hospitals', [AccountManagementController::class, 'getHospitals']);
    Route::get('/accounts/roles', [AccountManagementController::class, 'getRoles']);
    Route::get('/accounts/audit-logs', [AccountManagementController::class, 'getAuditLogs']);
    Route::delete('/accounts/{id}', [AccountManagementController::class, 'deleteAccount']);

    // إدارة المتبرعين والمستشفيات وبنوك الدم
    Route::apiResource('donors', DonorManagementController::class)->only(['index', 'show', 'destroy']);
    Route::apiResource('hospitals', HospitalManagementController::class)->only(['index', 'show', 'destroy']);
    Route::get('/hospitals-management', [HospitalManagementController::class, 'index']);
    Route::post('/hospitals/{id}/verify', [HospitalManagementController::class, 'verifyHospital']);
    Route::get('/blood-banks', [HospitalManagementController::class, 'index']);

    // إدارة الطوارئ والتبرعات
    Route::get('/requests', [RequestManagementController::class, 'index']);
    Route::get('/requests/{id}', [RequestManagementController::class, 'show']);
    Route::post('/requests/{id}/cancel', [RequestManagementController::class, 'cancelRequest']);
    Route::get('/donations', [RequestManagementController::class, 'index']);

    // الرسائل والتواصل
    Route::apiResource('messages', MessageController::class)->only(['index', 'show', 'destroy']);
    Route::post('/messages/{id}/reply', [MessageController::class, 'reply']);

    // الذكاء الاصطناعي ومراجعة الحسابات والمستشفيات (AI Fraud & Review)
    Route::post('/fraud/analyze', [FraudDetectionController::class, 'analyzeHospital']);
    Route::post('/fraud/review-account', [AccountManagementController::class, 'reviewAccount']);
    Route::post('/fraud/analyze-hospital', [FraudDetectionController::class, 'analyzeHospital']);

    // المكافآت والإعدادات
    Route::apiResource('rewards', RewardsController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('roles', RolesController::class)->only(['index', 'store']);

    // مسارات الإعدادات المتقدمة
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::post('/settings', [SettingsController::class, 'update']);
    Route::post('/settings/test-smtp', [SettingsController::class, 'testSmtp']);
});
