<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hospital\DashboardController;
use App\Http\Controllers\Hospital\BloodInventoryController;
use App\Http\Controllers\Hospital\EmergencyRequestController;
use App\Http\Controllers\Hospital\ActiveRequestsController;
use App\Http\Controllers\Hospital\DonorResponsesController;
use App\Http\Controllers\Hospital\MapController;
use App\Http\Controllers\Hospital\ReportsController;
use App\Http\Controllers\Hospital\EmergencyModeController;
use App\Http\Controllers\Hospital\HospitalSettingsController;

Route::middleware(['auth:sanctum', 'hospital'])->prefix('hospital')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // إدارة المخزون
    Route::get('/inventory', [BloodInventoryController::class, 'index']);
    Route::post('/inventory/update', [BloodInventoryController::class, 'update']);
    
    // نداءات الطوارئ
    Route::get('/requests', [EmergencyRequestController::class, 'index']);
    Route::post('/requests', [EmergencyRequestController::class, 'store']);
    Route::get('/requests/{id}', [EmergencyRequestController::class, 'show']);
    
    // متابعة الاستجابات
    Route::get('/active-requests', [ActiveRequestsController::class, 'index']);
    Route::get('/requests/{requestId}/responses', [DonorResponsesController::class, 'index']);
    
    // الخريطة والتقارير
    Route::post('/map/nearby-donors', [MapController::class, 'nearbyDonors']);
    Route::get('/reports', [ReportsController::class, 'index']);
    
    // وضع الطوارئ القصوى (ميزة متقدمة)
    Route::post('/emergency-mode/toggle', [EmergencyModeController::class, 'toggle']);

    // مسارات إعدادات الجهة الطبية (الملف التعريفي)
    Route::get('settings/profile', [HospitalSettingsController::class, 'index']);
    Route::put('settings/profile', [HospitalSettingsController::class, 'update']);
});

    

