<?php

namespace App\AI;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class HeatMapAnalysis
{
    public function generateHeatMap(array $requests, array $donors): array
    {
        try {
            $payload = [
                'requests' => $requests,
                'donors'   => $donors
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

            $scriptPath = base_path('scripts/python/heatmap_analysis.py');

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
            Log::error('HeatMapAnalysis Error: ' . $e->getMessage());
            return [];
        }
    }
}
