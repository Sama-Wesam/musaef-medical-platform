<?php

namespace App\AI;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ResponsePrediction
{
    public function getActiveDonors(array $donors)
    {
        $pythonPath = env('PYTHON_PATH', 'python3');

        $process = new Process([
            $pythonPath,
            base_path('scripts/python/responsiveness_predictor.py'),
            json_encode(['donors' => $donors], JSON_UNESCAPED_UNICODE)
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return json_decode($process->getOutput(), true);
    }
}
