<?php

namespace App\AI;

use App\Models\Donor;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DonationAnalyticsEngine
{
    /**
     * تجميع الإحصائيات الشاملة للنظام وتوليد التقرير الذكي
     */
    public function generateReport()
    {
        // 1. جلب بيانات الطلبات مع أسماء المستشفيات والفصائل
        $requests = DB::table('blood_requests')
            ->join('users', 'blood_requests.hospital_id', '=', 'users.id')
            ->join('blood_types', 'blood_requests.blood_type_id', '=', 'blood_types.id')
            ->select('users.name as hospital_name', 'blood_types.name as blood_type', 'blood_requests.units_required')
            ->get()->toArray();

        // 2. جلب بيانات المتبرعين وعناوينهم
        $donors = Donor::select('address')->get()->toArray();

        // 3. جلب بيانات المخزون الحالي للمستشفيات
        $inventory = DB::table('inventories')
            ->join('blood_types', 'inventories.blood_type_id', '=', 'blood_types.id')
            ->select('blood_types.name as blood_type', 'inventories.units_available')
            ->get()->toArray();

        $payload = [
            'requests'  => $requests,
            'donors'    => $donors,
            'inventory' => $inventory
        ];

        $pythonPath = env('PYTHON_PATH', 'python3');

        // 4. استدعاء سكريبت البايثون ومعالجة البيانات
        $process = new Process([
            $pythonPath,
            base_path('scripts/python/donation_analytics.py'),
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return json_decode($process->getOutput(), true);
    }
}
