<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BloodRequestController;
use App\Http\Controllers\API\DonationController;
use App\Http\Controllers\API\MedicalGuidelineController;

/*
|--------------------------------------------------------------------------
| API Routes - منصة مسعف الذكية
|--------------------------------------------------------------------------
*/

// ================== مسارات عامة (بدون تسجيل دخول) ==================

Route::post('/login', [AuthController::class, 'login']);

// تسجيل المتبرع الجديد مع استبيان الأهلية الطبية
Route::post('/register/donor', [AuthController::class, 'registerDonor']);

// تسجيل حساب المستشفيات والجهات الطبية
Route::post('/register/hospital', [AuthController::class, 'registerHospital']);

// جلب حالات الطوارئ النشطة لعرضها في الصفحة الرئيسية والخرائط العامة
Route::get('/emergencies/active', [BloodRequestController::class, 'index']);

// مسارات "مركز التبرع والإرشادات الطبية" العامة (مفتوحة للمتبرعين والزوار ومحسنة للشبكات الضعيفة)
Route::get('/medical-guidelines', [MedicalGuidelineController::class, 'index']);
Route::get('/medical-guidelines/{id}', [MedicalGuidelineController::class, 'show']);


// ================== مسارات محمية (للمستخدمين المسجلين فقط) ==================
Route::middleware('auth:sanctum')->group(function () {

    // تسجيل الخروج وإبطال التوكن الحالي
    Route::post('/logout', [AuthController::class, 'logout']);

    // مسار مشترك: قبول نداء الطوارئ والتوجه للمستشفى
    Route::post('/emergencies/{requestId}/accept', [DonationController::class, 'acceptEmergency']);

    // مسار مشترك: المستشفى تؤكد نجاح عملية التبرع وتمنح النقاط
    Route::post('/donations/confirm', [DonationController::class, 'store']);
});


// ================== استدعاء ملفات المسارات الفرعية ==================
require __DIR__.'/admin.php';
require __DIR__.'/hospital.php';
require __DIR__.'/donor.php';
