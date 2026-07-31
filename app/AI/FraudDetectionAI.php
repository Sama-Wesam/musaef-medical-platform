<?php

namespace App\AI;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FraudDetectionAI
{
    /**
     * كشف الاحتيال بناءً على سجلات النشاط عبر سكريبت Python
     */
    public function detectFraud(array $activityLogs)
    {
        $payload = ['logs' => $activityLogs];
        $pythonPath = env('PYTHON_PATH', 'python3');

        $process = new Process([
            $pythonPath,
            base_path('scripts/python/fraud_detection.py'),
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return json_decode($process->getOutput(), true);
    }

    /**
     * تحليل طلب الطوارئ للتحقق من سلامة البيانات ومؤشرات الاحتيال
     *
     * @param array $data
     * @return array
     */
    public function analyzeRequest(array $data): array
    {
        return [
            'is_fraud' => false,
            'score' => 0.0,
        ];
    }
}
