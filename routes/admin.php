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

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // إدارة المتبرعين
    Route::apiResource('donors', DonorManagementController::class)->only(['index', 'show', 'destroy']);
    
    // إدارة المستشفيات
    Route::apiResource('hospitals', HospitalManagementController::class)->only(['index', 'show', 'destroy']);
    Route::post('/hospitals/{id}/verify', [HospitalManagementController::class, 'verifyHospital']);
    
    // إدارة الطوارئ
    Route::get('/requests', [RequestManagementController::class, 'index']);
    Route::get('/requests/{id}', [RequestManagementController::class, 'show']);
    Route::post('/requests/{id}/cancel', [RequestManagementController::class, 'cancelRequest']);
    
    // الذكاء الاصطناعي وكشف الاحتيال
    Route::post('/fraud/analyze', [FraudDetectionController::class, 'analyzeHospital']);
    Route::get('/analytics/heatmap', [AnalyticsController::class, 'heatMapData']);
    Route::post('/analytics/forecast', [AnalyticsController::class, 'demandForecast']);
    
    // المكافآت والإعدادات
    Route::apiResource('rewards', RewardsController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('roles', RolesController::class)->only(['index', 'store']);
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::post('/settings', [SettingsController::class, 'update']);
});