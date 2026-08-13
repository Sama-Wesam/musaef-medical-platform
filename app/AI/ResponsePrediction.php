<?php

namespace App\AI;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ResponsePrediction
{
    public function getActiveDonors(array $donors): array
    {
        if (empty($donors)) {
            return [];
        }

        try {
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

            $scriptPath = base_path('scripts/python/responsiveness_predictor.py');

            $process = new Process([
                $pythonPath,
                $scriptPath,
                json_encode(['donors' => $donors], JSON_UNESCAPED_UNICODE)
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
            Log::error('ResponsePrediction Error: ' . $e->getMessage());
            return [];
        }
    }
}
