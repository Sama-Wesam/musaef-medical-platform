<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;
use App\Services\AIService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    use ApiResponseTrait;

    protected $aiService;

    public function __construct(AIService $aiService = null)
    {
        $this->aiService = $aiService;
    }

    /**
     * جلب كافة بيانات مركز التحليلات الذكية
     */
    public function index()
    {
        $kpi = [
            'critical_cases' => 236,
            'response_rate' => '92.7%',
            'total_requests' => '1,248',
            'total_donors' => '8,765',
        ];

        // الطلب حسب فصيلة الدم مع الألوان المعتمدة
        $bloodDemand = [
            ['type' => 'O+', 'count' => 452, 'color' => '#D32F2F'],
            ['type' => 'O-', 'count' => 298, 'color' => '#D32F2F'],
            ['type' => 'A+', 'count' => 215, 'color' => '#F97316'],
            ['type' => 'A-', 'count' => 142, 'color' => '#F97316'],
            ['type' => 'B+', 'count' => 98,  'color' => '#F59E0B'],
            ['type' => 'B-', 'count' => 69,  'color' => '#F59E0B'],
            ['type' => 'AB+', 'count' => 45, 'color' => '#16A34A'],
            ['type' => 'AB-', 'count' => 29, 'color' => '#16A34A'],
        ];

        // أكثر المستشفيات احتياجاً
        $neediestHospitals = [
            ['name' => 'مستشفى ناصر', 'percent' => 78, 'color' => '#DC2626'],
            ['name' => 'مستشفى القدس', 'percent' => 62, 'color' => '#F59E0B'],
            ['name' => 'مستشفى الأوروبي', 'percent' => 45, 'color' => '#EA580C'],
            ['name' => 'مستشفى الشفاء', 'percent' => 30, 'color' => '#16A34A'],
            ['name' => 'مستشفى القدس', 'percent' => 18, 'color' => '#16A34A'],
        ];

        // آخر التنبيهات الذكية
        $recentAlerts = [
            ['status' => 'عاجل', 'statusBadge' => 'bg-danger-subtle text-danger', 'type' => 'A+', 'hospital' => 'مستشفى ناصر', 'time' => '10:30 ص'],
            ['status' => 'متوسط', 'statusBadge' => 'bg-warning-subtle text-warning-emphasis', 'type' => 'B+', 'hospital' => 'مستشفى القدس', 'time' => '09:45 ص'],
            ['status' => 'مستقر', 'statusBadge' => 'bg-success-subtle text-success', 'type' => 'O-', 'hospital' => 'مستشفى الأوروبي', 'time' => '08:30 ص'],
            ['status' => 'مستقر', 'statusBadge' => 'bg-success-subtle text-success', 'type' => 'AB-', 'hospital' => 'مستشفى الشفاء', 'time' => '07:10 ص'],
        ];

        // إحصائيات الأداء
        $performanceStats = [
            'avg_response_time' => '18:24 دقيقة',
            'fulfillment_rate' => '92.6%',
            'daily_donation_rate' => '1,234 وحدة',
        ];

        return $this->successResponse([
            'kpi' => $kpi,
            'blood_demand' => $bloodDemand,
            'neediest_hospitals' => $neediestHospitals,
            'recent_alerts' => $recentAlerts,
            'performance' => $performanceStats
        ], 'تم جلب بيانات التحليلات الذكية بنجاح');
    }

    /**
     * جلب بيانات الخريطة الحرارية لأماكن الطوارئ
     */
    public function heatMapData()
    {
        $hotspots = $this->aiService ? $this->aiService->getLiveHeatMapData() : [];
        return $this->successResponse($hotspots, 'تم جلب بيانات الخريطة الحرارية');
    }

    /**
     * جلب توقعات احتياج المستشفيات للدم
     */
    public function demandForecast(Request $request)
    {
        $request->validate([
            'hospital_id' => 'required|integer|exists:hospitals,id',
            'blood_type_id' => 'required|integer|exists:blood_types,id',
        ]);

        $forecast = $this->aiService ? $this->aiService->getHospitalDemandForecast(
            $request->hospital_id,
            $request->blood_type_id
        ) : [];

        return $this->successResponse($forecast, 'تم جلب التوقعات بنجاح');
    }
}
