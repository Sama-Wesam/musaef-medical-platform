<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\AI\BloodDemandForecast;
use App\AI\DonationAnalyticsEngine;
use App\AI\EmergencyPriorityEngine;
use App\Models\Inventory;
use App\Models\BloodRequest;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    protected $statsService;

    public function __construct(StatisticsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * جلب إحصائيات لوحة تحكم المستشفى وتوقعات الذكاء الاصطناعي
     */
    public function index(Request $request, BloodDemandForecast $demandForecast, DonationAnalyticsEngine $analyticsEngine)
    {
        $hospitalId = $request->user()->hospital->id ?? null;

        // 1. جلب الإحصائيات الأساسية للمستشفى عبر الخدمة الموحدة
        try {
            $stats = $this->statsService->getHospitalDashboardStats($hospitalId);
        } catch (\Exception $e) {
            $stats = [
                'total_requests' => BloodRequest::where('hospital_id', $hospitalId)->count(),
                'critical_cases' => BloodRequest::where('hospital_id', $hospitalId)->where('urgency_level', 'critical')->count(),
                'available_units' => Inventory::where('hospital_id', $hospitalId)->sum('quantity'),
                'pending_requests' => BloodRequest::where('hospital_id', $hospitalId)->where('status', 'pending')->count()
            ];
        }

        // 2. فحص أثر المخزون والجلب الديناميكي للفصيلة الأكثر حرجاً
        $criticalInventory = Inventory::where('hospital_id', $hospitalId)
            ->where('quantity', '<', 5)
            ->with('bloodType')
            ->first();

        $bloodTypeToCheck = $criticalInventory ? $criticalInventory->bloodType->type : 'O-';
        $totalUnitsCount = Inventory::where('hospital_id', $hospitalId)->sum('quantity') ?: 10;
        $pendingRequestsCount = BloodRequest::where('hospital_id', $hospitalId)->where('status', 'pending')->count();

        // 3. تشغيل خوارزمية التنبؤ بنقص المخزون (Blood Demand Forecast AI)
        $aiPrediction = [];
        try {
            $forecastResult = $demandForecast->predictShortage(
                $bloodTypeToCheck,
                $totalUnitsCount,
                $pendingRequestsCount,
                48,
                true,
                1
            );

            $aiPrediction = [
                'title' => $forecastResult['title'] ?? "تم التنبؤ بارتفاع الطلب على فصيلة {$bloodTypeToCheck} خلال الـ 48 ساعة القادمة.",
                'description' => $forecastResult['description'] ?? 'زيادة حملات التبرع الفوري لتعويض النقص المتوقع في المخزون الحرجي.',
                'predicted_group' => $bloodTypeToCheck
            ];
        } catch (\Exception $e) {
            $aiPrediction = [
                'title' => "تنبيه: يوجد انخفاض متوقع في مخزون فصيلة {$bloodTypeToCheck}",
                'description' => 'يُعاني المخزون من انخفاض تدريجي، يرجى التنسيق لحملة تبرع عاجلة واستدعاء المتبرعين المطابقين.',
                'predicted_group' => $bloodTypeToCheck
            ];
        }

        // 4. جلب تنبيهات المخزون الحرجة الحقيقية من جدول المخازن
        $inventoryAlerts = Inventory::where('hospital_id', $hospitalId)
            ->where('quantity', '<=', 5)
            ->with('bloodType')
            ->get()
            ->map(function ($item) {
                return [
                    'blood_type' => $item->bloodType ? $item->bloodType->type : 'O-',
                    'status' => $item->quantity <= 2 ? 'حرج' : 'منخفض',
                    'units' => $item->quantity,
                    'priority' => $item->quantity <= 2 ? 'Critical' : 'High'
                ];
            })->toArray();

        // في حال عدم وجود تنبيهات حقيقية يتم إرجاع المصفوفة فارغة للواجهة
        if (empty($inventoryAlerts)) {
            $inventoryAlerts = [];
        }

        // 5. حساب توزيع فصائل الدم ديناميكياً بناءً على وحدات المخزن
        $totalInventoryUnits = Inventory::where('hospital_id', $hospitalId)->sum('quantity') ?: 1;
        $colorPalette = ['bg-danger', 'bg-primary', 'bg-success', 'bg-warning', 'bg-purple'];

        $bloodDistribution = Inventory::where('hospital_id', $hospitalId)
            ->with('bloodType')
            ->selectRaw('blood_type_id, SUM(quantity) as total')
            ->groupBy('blood_type_id')
            ->get()
            ->map(function ($item, $index) use ($totalInventoryUnits, $colorPalette) {
                $percent = round(($item->total / $totalInventoryUnits) * 100);
                return [
                    'name' => $item->bloodType ? $item->bloodType->type : 'O+',
                    'percentage' => "({$percent}%)",
                    'color' => $colorPalette[$index % count($colorPalette)]
                ];
            })->toArray();

        // 6. الرسوم البيانية للطلبات الشهريّة (ديناميكية حقيقية مجمعة حسب الشهر)
        $monthsMap = [
            1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr',
            5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'aug',
            9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dec'
        ];

        $monthlyCounts = BloodRequest::where('hospital_id', $hospitalId)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $maxCount = !empty($monthlyCounts) ? max($monthlyCounts) : 1;

        $monthlyRequests = [];
        for ($m = 1; $m <= 12; $m++) {
            $count = $monthlyCounts[$m] ?? 0;
            $height = round(($count / ($maxCount ?: 1)) * 100);
            $monthlyRequests[] = [
                'key' => $monthsMap[$m],
                'height' => $height < 10 && $count > 0 ? 15 : $height // حد أدنى للرؤية البصرية
            ];
        }

        $dashboardData = [
            'stats' => $stats,
            'blood_distribution' => !empty($bloodDistribution) ? $bloodDistribution : [
                ['name' => '+O', 'percentage' => '(0%)', 'color' => 'bg-danger']
            ],
            'monthly_requests' => $monthlyRequests,
            'inventory_alerts' => $inventoryAlerts,
            'ai_prediction' => $aiPrediction
        ];

        return $this->successResponse($dashboardData, 'تم جلب الإحصائيات بنجاح');
    }
}
