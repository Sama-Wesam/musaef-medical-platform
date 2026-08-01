<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BloodRequestController;
use App\Http\Controllers\API\DonationController;
use App\Http\Controllers\API\MedicalGuidelineController;
use App\Http\Controllers\API\PublicController;
use App\Http\Controllers\Donor\DonationCenterController;
use App\Http\Controllers\Donor\DonationHistoryController;

/*
|--------------------------------------------------------------------------
| API Routes - منصة مسعف
|--------------------------------------------------------------------------
*/

// 1. المسارات العامة (Public Routes)
Route::prefix('public')->group(function () {
    Route::get('/home-stats', [PublicController::class, 'getHomeStats']);
    Route::get('/urgent-requests', [PublicController::class, 'getUrgentRequests']);
    Route::get('/partners', [PublicController::class, 'getPartnersHospitals']);
    Route::post('/contact', [PublicController::class, 'sendContactMessage']);
    Route::get('/nearby-facilities', [PublicController::class, 'getNearbyFacilities']);
});

// 2. مسارات المصادقة العامة (Guest Auth Routes)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/register/donor', [AuthController::class, 'registerDonor']);
Route::post('/register/hospital', [AuthController::class, 'registerHospital']);

// 3. مسارات الاستعلام العام والطوارئ
Route::get('/emergencies/active', [BloodRequestController::class, 'index']);
Route::get('/medical-guidelines', [MedicalGuidelineController::class, 'index']);
Route::get('/medical-guidelines/{id}', [MedicalGuidelineController::class, 'show']);
Route::get('/history', [DonationHistoryController::class, 'index']);
Route::get('/rewards-and-card', [DonationHistoryController::class, 'index']);

// 4. المسارات المحمية بواسطة Sanctum Token
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/emergencies/{requestId}/accept', [DonationController::class, 'acceptEmergency']);
    Route::post('/donations/confirm', [DonationController::class, 'store']);

    Route::prefix('donation-center')->group(function () {
        Route::get('/ai-recommendations', [DonationCenterController::class, 'getAiRecommendations']);
        Route::get('/heatmap', [DonationCenterController::class, 'getHeatMapData']);
        Route::get('/requests', [DonationCenterController::class, 'getAllRequests']);
    });

    // استدعاء ملفات المسارات الفرعية للأدوار المختلفة
    require __DIR__.'/donor.php';
    require __DIR__.'/hospital.php';
    require __DIR__.'/admin.php';
});
