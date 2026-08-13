<?php

namespace App\AI;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class EmergencyPriorityEngine
{
    /**
     * استقبال مصفوفة الطلبات وإرسالها للذكاء الاصطناعي لترتيبها
     */
    public function sortRequests(array $requests): array
    {
        if (empty($requests)) {
            return [];
        }

        try {
            $payload = ['requests' => $requests];

            // تحديد مسار بايثون بشكل مرن يدعم جميع بيئات التشغيل (Windows / Linux)
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

            $scriptPath = base_path('scripts/python/emergency_priority.py');

            $process = new Process([
                $pythonPath,
                $scriptPath,
                json_encode($payload, JSON_UNESCAPED_UNICODE)
            ]);

            $process->setWorkingDirectory(base_path());
            $process->setTimeout(5);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output = json_decode($process->getOutput(), true);
            return is_array($output) ? $output : $requests;

        } catch (\Throwable $e) {
            Log::error('EmergencyPriorityEngine Error: ' . $e->getMessage());
            return $requests;
        }
    }
}
