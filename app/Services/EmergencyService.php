<?php

namespace App\Services;

use App\Repositories\EmergencyRepository;
use App\Models\BloodRequest;
use App\Events\EmergencyCreated;
use App\Events\EmergencyResolved;
use App\Enums\RequestStatus;
use App\AI\FraudDetectionAI;
use Exception;

class EmergencyService
{
    protected $emergencyRepository;
    protected $fraudDetectionAI;

    public function __construct(EmergencyRepository $emergencyRepository, FraudDetectionAI $fraudDetectionAI)
    {
        $this->emergencyRepository = $emergencyRepository;
        $this->fraudDetectionAI = $fraudDetectionAI;
    }

    public function createEmergencyRequest(array $data, $hospital)
    {
        // 1. فحص الاحتيال بالذكاء الاصطناعي
        $fraudCheck = $this->fraudDetectionAI->analyzeRequest($hospital, $data['units_required']);
        
        if ($fraudCheck['is_suspicious'] && $fraudCheck['fraud_score'] > 50) {
            throw new Exception("تم حظر الطلب للاشتباه بسلوك غير معتاد (Spam).");
        }

        // 2. إنشاء الطلب
        $data['hospital_id'] = $hospital->id;
        $data['status'] = RequestStatus::SEARCHING->value;
        $request = $this->emergencyRepository->createRequest($data);

        // 3. إطلاق حدث الطوارئ (سيقوم بتشغيل AI المطابقة وإرسال الإشعارات)
        event(new EmergencyCreated($request));

        return $request;
    }

    public function markAsCompleted(int $requestId)
    {
        $request = $this->emergencyRepository->findById($requestId);
        
        if ($request && $request->status !== RequestStatus::COMPLETED->value) {
            $this->emergencyRepository->updateStatus($requestId, RequestStatus::COMPLETED->value);
            
            // إخفاء الحالة من الخريطة المباشرة
            event(new EmergencyResolved($request));
            return true;
        }
        return false;
    }
}