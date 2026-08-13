<?php

namespace App\AI;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FacilityRecommendationEngine
{
    /**
     * جلب أفضل المستشفيات وبنوك الدم التي تحتوي على الفصيلة المطلوبة
     */
    public function getRecommendations(array $requestingHospital, string $bloodType, array $otherFacilities): array
    {
        try {
            $payload = [
                'requesting_hospital' => $requestingHospital,
                'blood_type'          => $bloodType,
                'facilities'          => $otherFacilities
            ];

            $pythonPath = env('PYTHON_PATH');
            if (!$pythonPath) {
                $venvWin = base_path('.venv/Scripts/python.exe');
                $venvLinux = base_path('.venv/bin/python');

                if (file_exists($venvWin)) {
                    $pythonPath = $venvWin;
                } elseif (file_exists($venvLinux)) {
                    $pythonPath = $venvLinux;
                } else {
                    $pythonPath = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
                }
            }

            $scriptPath = base_path('scripts/python/facility_recommendation.py');

            $process = new Process([
                $pythonPath,
                $scriptPath,
                json_encode($payload, JSON_UNESCAPED_UNICODE)
            ]);

            $process->setWorkingDirectory(base_path());
            $process->setTimeout(10);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $result = json_decode($process->getOutput(), true);
            return is_array($result) ? $result : [];

        } catch (\Throwable $e) {
            Log::error('FacilityRecommendationEngine Error: ' . $e->getMessage());
            return [];
        }
    }
}
