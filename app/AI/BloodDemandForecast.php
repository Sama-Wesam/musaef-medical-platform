<?php

namespace App\AI;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BloodDemandForecast
{
    /**
     * تشغيل خوارزمية التنبؤ بنقص مخزون فصيلة دم محددة
     */
    public function predictShortage(string $bloodType, int $currentStock, int $dailyConsumption, int $pendingRequests, bool $isEmergency, int $season = 1): array
    {
        $payload = [
            'blood_type'        => $bloodType,
            'current_stock'     => $currentStock,
            'daily_consumption' => $dailyConsumption,
            'pending_requests'  => $pendingRequests,
            'is_emergency'      => $isEmergency ? 1 : 0,
            'season'            => $season // 1 الشتاء، 2 الربيع، 3 الصيف، 4 الخريف
        ];

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

            $scriptPath = base_path('scripts/python/blood_prediction.py');

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

            $output = json_decode($process->getOutput(), true);
            return is_array($output) ? $output : [];

        } catch (\Throwable $e) {
            Log::error('BloodDemandForecast Error: ' . $e->getMessage(), ['payload' => $payload]);

            // قيمة احتياطية آمنة متوافقة مع الهيكل المتوقع للواجهة الأمامية
            return [
                'blood_type'     => $bloodType,
                'predicted_days' => $dailyConsumption > 0 ? round($currentStock / $dailyConsumption, 1) : 99,
                'status'         => 'غير معروف',
                'message'        => 'تعذر التنبؤ حالياً بسبب خطأ في الخادم.',
                'fallback'       => true
            ];
        }
    }
}
