<?php

namespace App\AI;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FacilityRecommendationEngine
{
    /**
     * جلب أفضل المستشفيات وبنوك الدم التي تحتوي على الفصيلة المطلوبة
     */
    public function getRecommendations(array $requestingHospital, string $bloodType, array $otherFacilities)
    {
        $payload = [
            'requesting_hospital' => $requestingHospital,
            'blood_type'          => $bloodType,
            'facilities'          => $otherFacilities
        ];

        $pythonPath = env('PYTHON_PATH', 'python3');

        $process = new Process([
            $pythonPath,
            base_path('scripts/python/facility_recommendation.py'),
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return json_decode($process->getOutput(), true);
    }
}
