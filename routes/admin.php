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
use App\Http\Controllers\Admin\MedicalGuidelineController;
use App\Http\Controllers\Admin\EmergencyRadarController;
use App\Http\Controllers\Admin\AccountManagementController;

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // مسارات رادار الطوارئ المباشر
    Route::get('/emergency-radar', [EmergencyRadarController::class, 'index']);
    Route::post('/emergency-radar/{id}/trigger-response', [EmergencyRadarController::class, 'triggerResponse']);

    // مركز التحليلات الذكية
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    Route::get('/analytics/heatmap', [AnalyticsController::class, 'heatMapData']);
    Route::post('/analytics/forecast', [AnalyticsController::class, 'demandForecast']);

    // مسارات إدارة الحسابات
    Route::get('/accounts/donors', [AccountManagementController::class, 'getDonors']);
    Route::get('/accounts/hospitals', [AccountManagementController::class, 'getHospitals']);
    Route::get('/accounts/roles', [AccountManagementController::class, 'getRoles']);
    Route::get('/accounts/audit-logs', [AccountManagementController::class, 'getAuditLogs']);
    Route::delete('/accounts/{id}', [AccountManagementController::class, 'deleteAccount']);

    // إدارة المتبرعين والمستشفيات
    Route::apiResource('donors', DonorManagementController::class)->only(['index', 'show', 'destroy']);
    Route::apiResource('hospitals', HospitalManagementController::class)->only(['index', 'show', 'destroy']);
    Route::post('/hospitals/{id}/verify', [HospitalManagementController::class, 'verifyHospital']);

    // إدارة الطوارئ
    Route::get('/requests', [RequestManagementController::class, 'index']);
    Route::get('/requests/{id}', [RequestManagementController::class, 'show']);
    Route::post('/requests/{id}/cancel', [RequestManagementController::class, 'cancelRequest']);

    // الذكاء الاصطناعي ومركز التبرع
    Route::post('/fraud/analyze', [FraudDetectionController::class, 'analyzeHospital']);
    Route::apiResource('medical-guidelines', MedicalGuidelineController::class);

    // المكافآت والإعدادات
    Route::apiResource('rewards', RewardsController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('roles', RolesController::class)->only(['index', 'store']);

    // مسارات الإعدادات المتقدمة
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::post('/settings', [SettingsController::class, 'update']);
    Route::post('/settings/test-smtp', [SettingsController::class, 'testSmtp']);
});
