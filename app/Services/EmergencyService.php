<?php

namespace App\Services;

use App\Repositories\Contracts\EmergencyRepositoryInterface;
use App\Events\EmergencyCreated;
use App\Events\EmergencyResolved;
use App\Enums\RequestStatus;
use App\AI\FraudDetectionAI;
use Exception;

class EmergencyService
{
    public function __construct(
        protected EmergencyRepositoryInterface $emergencyRepository,
        protected FraudDetectionAI $fraudDetectionAI
    ) {}

    public function createEmergencyRequest(array $data, object $hospital)
    {
        $fraudCheck = $this->fraudDetectionAI->analyzeRequest($data);

        if (isset($fraudCheck['is_fraud']) && $fraudCheck['is_fraud'] === true) {
            throw new Exception("تم حظر الطلب للاشتباه بسلوك غير معتاد (Spam).");
        }

        $searchingStatus = defined('App\Enums\RequestStatus::SEARCHING') ? RequestStatus::SEARCHING->value : 'searching';

        $data['hospital_id'] = $hospital->id;
        $data['status']      = $searchingStatus;

        $request = $this->emergencyRepository->createRequest($data);

        event(new EmergencyCreated($request));

        return $request;
    }

    public function markAsCompleted(int $requestId): bool
    {
        $request = $this->emergencyRepository->findById($requestId);
        $completedStatus = defined('App\Enums\RequestStatus::COMPLETED') ? RequestStatus::COMPLETED->value : 'completed';

        // معالجة مرنة وشاملة لحالة الطلب سواء كانت Enum أو String
        $currentStatus = match (true) {
            $request?->status instanceof \UnitEnum => $request->status->value ?? $request->status->name,
            is_object($request?->status) && method_exists($request->status, 'value') => $request->status->value,
            default => (string) ($request?->status ?? ''),
        };

        if ($request && $currentStatus !== $completedStatus) {
            $this->emergencyRepository->updateStatus($requestId, $completedStatus);

            event(new EmergencyResolved($request));
            return true;
        }

        return false;
    }
}
