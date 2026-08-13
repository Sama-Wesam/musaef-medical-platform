<?php

namespace App\AI;

use App\Models\Donor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DonationAnalyticsEngine
{
    /**
     * تجميع الإحصائيات الشاملة للنظام وتوليد التقرير الذكي
     */
    public function generateReport(): array
    {
        try {
            // 1. جلب بيانات الطلبات مع أسماء المستشفيات والفصائل
            $requests = DB::table('blood_requests')
                ->join('users', 'blood_requests.hospital_id', '=', 'users.id')
                ->join('blood_types', 'blood_requests.blood_type_id', '=', 'blood_types.id')
                ->select('users.name as hospital_name', 'blood_types.name as blood_type', 'blood_requests.units_required')
                ->get()->toArray();

            // 2. جلب بيانات المتبرعين وعناوينهم
            $donors = Donor::select('address')->get()->toArray();

            // 3. جلب بيانات المخزون الحالي للمستشفيات
            $inventory = DB::table('blood_inventories')
                ->join('blood_types', 'blood_inventories.blood_type_id', '=', 'blood_types.id')
                ->select('blood_types.name as blood_type', 'blood_inventories.units_available')
                ->get()->toArray();

            $payload = [
                'requests'  => $requests,
                'donors'    => $donors,
                'inventory' => $inventory
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

            // 4. استدعاء سكريبت البايثون ومعالجة البيانات مع تعيين المجلد الرئيسي
            $process = new Process([
                $pythonPath,
                base_path('scripts/python/donation_analytics.py'),
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
            Log::error('DonationAnalyticsEngine Error: ' . $e->getMessage());

            return [
                'status' => 'error',
                'message' => 'تعذر توليد التقرير الذكي حالياً.',
                'analytics' => []
            ];
        }
    }
}
