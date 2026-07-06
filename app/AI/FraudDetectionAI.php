<?php

namespace App\AI;

use App\Models\BloodRequest;
use App\Models\Hospital;
use Carbon\Carbon;

class FraudDetectionAI
{
    /**
     * تحليل طلب الدم للتحقق من عدم وجود سلوك احتيالي أو إزعاج
     */
    public function analyzeRequest(Hospital $hospital, int $unitsRequested): array
    {
        $isSuspicious = false;
        $flags = [];

        // 1. التحقق من عدد الطلبات في آخر ساعة (تجنب الـ Spam)
        $requestsInLastHour = BloodRequest::where('hospital_id', $hospital->id)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->count();

        if ($requestsInLastHour > 5) {
            $isSuspicious = true;
            $flags[] = 'High frequency of requests in a short time (Spam Detected).';
        }

        // 2. التحقق من الكمية المطلوبة (غير منطقي طلب 100 وحدة فجأة من مستشفى صغير)
        if ($unitsRequested > 50) {
            $isSuspicious = true;
            $flags[] = 'Unusually high amount of blood units requested at once.';
        }

        // 3. التحقق من حالة توثيق المستشفى
        if (!$hospital->is_verified) {
            $isSuspicious = true;
            $flags[] = 'Request generated from an unverified hospital account.';
        }

        return [
            'is_suspicious' => $isSuspicious,
            'fraud_score' => count($flags) * 33.3, // كل علامة تزيد نسبة الشك
            'flags' => $flags
        ];
    }
}