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
use App\Http\Controllers\Hospital\NotificationController;

Route::middleware(['auth:sanctum', 'hospital'])->prefix('hospital')->group(function () {

    // ⚡ مسار الـ Polling لتحديث استجابات المتبرعين والمخزون وحالة الطوارئ مع تخفيف الضغط
    Route::get('/polling/live-updates', [DashboardController::class, 'livePollingUpdates']);

    // مسار لوحة تحكم المستشفى وبنك الدم الرئيسي
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // مسارات تقارير الذكاء الاصطناعي
    Route::get('/ai-forecast-report', [DashboardController::class, 'index']);
    Route::post('/ai-forecast-report', [DashboardController::class, 'index']);

    // مسارات الإشعارات والتنبيهات للمستشفى
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    // إدارة المخزون
    Route::get('/inventory', [BloodInventoryController::class, 'index']);
    Route::post('/inventory/update', [BloodInventoryController::class, 'update']);

    // نداءات الطوارئ
    Route::get('/requests', [EmergencyRequestController::class, 'index']);
    Route::post('/requests', [EmergencyRequestController::class, 'store']);
    Route::get('/requests/{id}', [EmergencyRequestController::class, 'show']);

    // 🛠️ إضافة مسارات تحديث حالة الطلب وتأكيد التبرع لمنع خطأ 404
    Route::put('/requests/{id}/status', [EmergencyRequestController::class, 'updateStatus']);
    Route::post('/requests/{id}/status', [EmergencyRequestController::class, 'updateStatus']);
    Route::put('/emergency-requests/{id}/status', [EmergencyRequestController::class, 'updateStatus']);
    Route::post('/emergency-requests/{id}/status', [EmergencyRequestController::class, 'updateStatus']);

    // قبول ورفض طلبات الطوارئ
    Route::post('/requests/{id}/accept', [EmergencyRequestController::class, 'accept']);
    Route::post('/requests/{id}/reject', [EmergencyRequestController::class, 'reject']);

    // متابعة الاستجابات
    Route::get('/active-requests', [ActiveRequestsController::class, 'index']);
    Route::get('/requests/{requestId}/responses', [DonorResponsesController::class, 'index']);

    // الخريطة والتقارير
    Route::post('/map/nearby-donors', [MapController::class, 'nearbyDonors']);
    Route::get('/reports', [ReportsController::class, 'index']);

    // وضع الطوارئ القصوى
    Route::post('/emergency-mode/toggle', [EmergencyModeController::class, 'toggle']);

    // مسارات إعدادات الجهة الطبية
    Route::get('settings/profile', [HospitalSettingsController::class, 'index']);
    Route::put('settings/profile', [HospitalSettingsController::class, 'update']);
});
