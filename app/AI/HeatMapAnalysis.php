<?php

namespace App\AI;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class HeatMapAnalysis
{
    public function generateHeatMap(array $requests, array $donors)
    {
        $payload = [
            'requests' => $requests, // تحتوي على lat و lon
            'donors'   => $donors   // تحتوي على lat و lon
        ];

        $pythonPath = env('PYTHON_PATH', 'python3');

        $process = new Process([
            $pythonPath,
            base_path('scripts/python/heatmap_analysis.py'),
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return json_decode($process->getOutput(), true);
    }
}
