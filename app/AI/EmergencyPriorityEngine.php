<?php

namespace App\AI;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class EmergencyPriorityEngine
{
    /**
     * استقبال مصفوفة الطلبات وإرسالها للذكاء الاصطناعي لترتيبها
     */
    public function sortRequests(array $requests)
    {
        $payload = [
            'requests' => $requests
        ];

        $pythonPath = env('PYTHON_PATH', 'python3');

        $process = new Process([
            $pythonPath,
            base_path('scripts/python/emergency_priority.py'),
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();
        return json_decode($output, true);
    }
}
