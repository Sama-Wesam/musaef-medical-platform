<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BloodRequestController;
use App\Http\Controllers\API\DonationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ================== مسارات عامة (بدون تسجيل دخول) ==================

Route::post('/login', [AuthController::class, 'login']);

// يستقبل البيانات الأساسية + إجابات الاستبيان الصحي دفعة واحدة
Route::post('/register/donor', [AuthController::class, 'registerDonor']);

// افتراض وجود مسار لتسجيل المستشفيات
Route::post('/register/hospital', [AuthController::class, 'registerHospital']); 

// جلب حالات الطوارئ النشطة لعرضها في الصفحة الرئيسية (Landing Page)
Route::get('/emergencies/active', [BloodRequestController::class, 'index']);


// ================== مسارات محمية (للمستخدمين المسجلين فقط) ==================
Route::middleware('auth:sanctum')->group(function () {
    
    // مسار تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // مسار مشترك: قبول نداء الطوارئ من المتبرع
    Route::post('/emergencies/{requestId}/accept', [DonationController::class, 'acceptEmergency']);

    // مسار مشترك: المستشفى تؤكد نجاح عملية التبرع وتمنح النقاط
    Route::post('/donations/confirm', [DonationController::class, 'store']);
});


// ================== استدعاء ملفات المسارات الفرعية ==================
// لمنع تكدس الكود في ملف واحد
require __DIR__.'/admin.php';
require __DIR__.'/hospital.php';
require __DIR__.'/donor.php';