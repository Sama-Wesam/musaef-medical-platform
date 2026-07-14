<?php

namespace App\AI;

use Symfony\Component\Process\Process;

class FraudDetectionAI
{
    public function detectFraud(array $activityLogs)
    {
        $payload = ['logs' => $activityLogs];
        $process = new Process([
            'python',
            base_path('scripts/python/fraud_detection.py'),
            json_encode($payload)
        ]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Symfony\Component\Process\Exception\ProcessFailedException($process);
        }

        return json_decode($process->getOutput(), true);
    }
}
