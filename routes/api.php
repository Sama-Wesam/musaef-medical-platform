<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BloodRequestController;
use App\Http\Controllers\API\DonationController;
use App\Http\Controllers\API\MedicalGuidelineController;
use App\Http\Controllers\API\PublicController;
use App\Http\Controllers\API\DonorController;
use App\Http\Controllers\Donor\ProfileController;

Route::prefix('public')->group(function () {
    Route::get('/home-stats', [PublicController::class, 'getHomeStats']);
    Route::get('/urgent-requests', [PublicController::class, 'getUrgentRequests']);
    Route::get('/partners', [PublicController::class, 'getPartnersHospitals']);
    Route::post('/contact', [PublicController::class, 'sendContactMessage']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register/donor', [AuthController::class, 'registerDonor']);
Route::post('/register/hospital', [AuthController::class, 'registerHospital']);
Route::get('/emergencies/active', [BloodRequestController::class, 'index']);
Route::get('/medical-guidelines', [MedicalGuidelineController::class, 'index']);
Route::get('/medical-guidelines/{id}', [MedicalGuidelineController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/emergencies/{requestId}/accept', [DonationController::class, 'acceptEmergency']);
    Route::post('/donations/confirm', [DonationController::class, 'store']);

    Route::prefix('donor')->group(function () {
        Route::get('/home-stats', [DonorController::class, 'homeStats']);
        Route::get('/urgent-requests', [DonorController::class, 'urgentRequests']);
        Route::get('/rewards-and-card', [DonorController::class, 'rewardsAndCard']);
        Route::get('/donation-history', [DonorController::class, 'donationHistory']);

        // مسارات الإشعارات 
        Route::get('/notifications', [DonorController::class, 'notifications']);
        Route::post('/notifications/mark-as-read', [DonorController::class, 'markNotificationsAsRead']);

        // مسارات ملف المتبرع الشخصي
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/profile/update', [ProfileController::class, 'update']);
        Route::post('/health-questionnaire', [ProfileController::class, 'updateHealthQuestionnaire']);
    });

    require __DIR__.'/hospital.php';
    require __DIR__.'/admin.php';
});
