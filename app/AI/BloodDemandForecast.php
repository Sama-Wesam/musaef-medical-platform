<?php

namespace App\AI;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BloodDemandForecast
{
    /**
     * تشغيل خوارزمية التنبؤ بنقص مخزون فصيلة دم محددة
     */
    public function predictShortage(string $bloodType, int $currentStock, int $dailyConsumption, int $pendingRequests, bool $isEmergency, int $season = 1)
    {
        $payload = [
            'blood_type'        => $bloodType,
            'current_stock'     => $currentStock,
            'daily_consumption' => $dailyConsumption,
            'pending_requests'  => $pendingRequests,
            'is_emergency'      => $isEmergency ? 1 : 0,
            'season'            => $season // 1 الشتاء، 2 الربيع، 3 الصيف، 4 الخريف
        ];

        $pythonPath = env('PYTHON_PATH', 'python3');

        // استدعاء ملف البايثون
        $process = new Process([
            $pythonPath,
            base_path('scripts/python/blood_prediction.py'),
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // إرجاع مخرجات الذكاء الاصطناعي
        $output = $process->getOutput();
        return json_decode($output, true);
    }
}
