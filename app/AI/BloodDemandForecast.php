<?php

namespace App\AI;

use App\Models\BloodRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BloodDemandForecast
{
    /**
     * التنبؤ باحتياج فصيلة معينة لمستشفى معين خلال الأيام الـ 7 القادمة
     */
    public function forecastDemand(int $hospitalId, int $bloodTypeId)
    {
        // جلب استهلاك الدم في آخر 30 يوماً
        $historicalData = BloodRequest::where('hospital_id', $hospitalId)
            ->where('blood_type_id', $bloodTypeId)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(units_required) as total_units'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        if ($historicalData->count() < 5) {
            return ['status' => 'insufficient_data', 'forecast_units' => 0];
        }

        // حساب المتوسط المتحرك (Moving Average) البسيط
        $totalUnits = $historicalData->sum('total_units');
        $averagePerDay = $totalUnits / 30;

        // توقع الاحتياج للأيام السبعة القادمة مع إضافة هامش طوارئ (15%)
        $expectedNext7Days = ceil($averagePerDay * 7 * 1.15);

        return [
            'status' => 'success',
            'historical_average_per_day' => round($averagePerDay, 2),
            'forecast_next_7_days' => $expectedNext7Days,
            'risk_level' => $expectedNext7Days > 20 ? 'High' : 'Normal'
        ];
    }
}