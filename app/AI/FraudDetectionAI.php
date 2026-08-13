<?php

namespace App\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FraudDetectionAI
{
    /**
     * الحصول على مسار مترجم بايثون بأمان
     */
    private function getPythonPath(): string
    {
        $pythonPath = env('PYTHON_PATH');
        if ($pythonPath) {
            return $pythonPath;
        }

        $venvWin = base_path('.venv/Scripts/python.exe');
        $venvLinux = base_path('.venv/bin/python');

        if (file_exists($venvWin)) {
            return $venvWin;
        } elseif (file_exists($venvLinux)) {
            return $venvLinux;
        }

        return PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
    }

    /**
     * كشف الاحتيال بناءً على سجلات النشاط عبر سكريبت Python أو خدمة Microservice
     */
    public function detectFraud(array $activityLogs): array
    {
        if (empty($activityLogs)) {
            return [];
        }

        try {
            $serviceUrl = config('services.ai.url', env('AI_SERVICE_URL'));

            if (!empty($serviceUrl)) {
                $response = Http::timeout(3)->post("{$serviceUrl}/detect-fraud", [
                    'logs' => $activityLogs,
                ]);

                if ($response->successful()) {
                    return $response->json() ?? [];
                }
            }

            $payload = ['logs' => $activityLogs];
            $pythonPath = $this->getPythonPath();
            $scriptPath = base_path('scripts/python/fraud_detection.py');

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

            $output = json_decode($process->getOutput(), true);

            return is_array($output) ? $output : [];

        } catch (\Throwable $e) {
            Log::error('FraudDetectionAI (detectFraud) Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * تحليل طلب الطوارئ الفردي للتحقق من سلامة البيانات ومؤشرات الاحتيال
     */
    public function analyzeRequest(array $data): array
    {
        try {
            $serviceUrl = config('services.ai.url', env('AI_SERVICE_URL'));

            if (!empty($serviceUrl)) {
                $response = Http::timeout(2)->post("{$serviceUrl}/analyze-request", $data);

                if ($response->successful()) {
                    return $response->json() ?? ['is_fraud' => false, 'score' => 0.0];
                }
            }

            $scriptPath = base_path('scripts/python/fraud_detection.py');
            if (!file_exists($scriptPath)) {
                return ['is_fraud' => false, 'score' => 0.0];
            }

            $payload = ['logs' => [$data]];
            $pythonPath = $this->getPythonPath();

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

            if (is_array($result) && !empty($result)) {
                if (isset($result['error'])) {
                    throw new \Exception($result['error']);
                }

                return [
                    'is_fraud' => true,
                    'score'    => 0.85,
                    'details'  => $result[0] ?? $result,
                ];
            }

            return [
                'is_fraud' => false,
                'score'    => 0.0,
            ];

        } catch (\Throwable $e) {
            Log::error('FraudDetectionAI (analyzeRequest) Error: ' . $e->getMessage(), [
                'request_data' => $data,
            ]);

            return [
                'is_fraud' => false,
                'score'    => 0.0,
                'fallback' => true,
            ];
        }
    }
}
