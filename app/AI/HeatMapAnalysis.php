<?php

namespace App\AI;

use Symfony\Component\Process\Process;

class HeatMapAnalysis
{
    public function generateHeatMap(array $requests, array $donors)
    {
        $payload = [
            'requests' => $requests, // تحتوي على lat و lon
            'donors'   => $donors   // تحتوي على lat و lon
        ];

        $process = new Process(['python', base_path('scripts/python/heatmap_analysis.py'), json_encode($payload)]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Symfony\Component\Process\Exception\ProcessFailedException($process);
        }

        return json_decode($process->getOutput(), true);
    }
}
