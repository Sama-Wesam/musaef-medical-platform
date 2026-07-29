<?php

namespace App\AI;

use Symfony\Component\Process\Process;

class ResponsePrediction
{
    public function getActiveDonors(array $donors)
    {
        $process = new Process([
            'python',
            base_path('scripts/python/responsiveness_predictor.py'),
            json_encode(['donors' => $donors])
        ]);

        $process->run();
        return json_decode($process->getOutput(), true);
    }
}
