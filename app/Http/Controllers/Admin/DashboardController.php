<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\Hospital;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Traits\ApiResponseTrait;
use App\AI\HeatMapAnalysis;
use App\AI\EmergencyPriorityEngine;
use App\AI\DonationAnalyticsEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    /**
     * جلب إحصائيات لوحة تحكم المسؤول والإدارة العليا المباشرة من قاعدة البيانات
     */
    public function index(HeatMapAnalysis $heatMapEngine, EmergencyPriorityEngine $priorityEngine, DonationAnalyticsEngine $analyticsEngine)
    {
        // 1. حساب إحصائيات قاعدة البيانات الحقيقية
        $donorsCount = Donor::count();
        $hospitalsCount = Hospital::count();
        $requestsCount = BloodRequest::count();
        $donationsCount = Donation::count();

        // 2. حساب الحالات الحرجة النشطة بمرونة (التوافق مع emergency_level أو urgency_level)
        $criticalCount = BloodRequest::whereIn('status', ['pending', 'searching', 'open'])
            ->where(function ($query) {
                $query->where('emergency_level', 'critical')
                      ->orWhere('emergency_level', 'high');
            })
            ->count();

        if ($criticalCount === 0) {
            $criticalCount = BloodRequest::whereIn('status', ['pending', 'searching', 'open'])->count();
        }

        $stats = [
            'donors_count'     => $donorsCount,
            'hospitals_count'  => $hospitalsCount,
            'total_requests'   => $requestsCount,
            'total_donations'  => $donationsCount,
            'critical_cases'   => $criticalCount,
        ];

        // 3. التوزيع الحقيقي لطلبات فصائل الدم
        $totalBloodRequests = BloodRequest::count() ?: 1;
        $bloodDistributionRaw = DB::table('blood_requests')
            ->join('blood_types', 'blood_requests.blood_type_id', '=', 'blood_types.id')
            ->select('blood_types.name as blood_type', DB::raw('count(*) as count'))
            ->groupBy('blood_types.name')
            ->get();

        $bloodDistribution = $bloodDistributionRaw->map(function ($item) use ($totalBloodRequests) {
            return [
                'blood_type' => $item->blood_type,
                'count'      => $item->count,
                'percentage' => round(($item->count / $totalBloodRequests) * 100, 1)
            ];
        })->toArray();

        // 4. نقاط رادار الطوارئ المباشر
        $radarRequests = BloodRequest::with(['hospital', 'bloodType'])
            ->whereIn('status', ['pending', 'searching', 'open'])
            ->latest()
            ->take(20)
            ->get();

        $radarPoints = $radarRequests->map(function ($req) {
            $lat = $req->latitude ?? $req->hospital->latitude ?? null;
            $lng = $req->longitude ?? $req->hospital->longitude ?? null;
            $level = $req->emergency_level ?? 'normal';

            return [
                'id'        => $req->id,
                'lat'       => $lat !== null ? (float) $lat : null,
                'lng'       => $lng !== null ? (float) $lng : null,
                'intensity' => in_array($level, ['critical', 'high']) ? 1.0 : 0.6,
                'bloodType' => $req->bloodType->name ?? 'N/A',
                'name'      => $req->hospital->name ?? $req->hospital->facility_name ?? 'طلب طارئ مباشر',
            ];
        })->toArray();

        // 5. تطور الحالات الطارئة خلال آخر 6 أشهر
        $emergencyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = strtolower($date->format('M'));
            $count = BloodRequest::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $emergencyTrend[] = [
                'monthKey' => $monthKey,
                'count'    => $count,
                'label'    => $date->format('Y-m')
            ];
        }

        // 6. النشاط الأسبوعي الحقيقي للمنصة
        $weeklyActivity = [];
        $days = ['sat', 'sun', 'mon', 'tue', 'wed', 'thu', 'fri'];
        foreach ($days as $index => $dayKey) {
            $dayDate = Carbon::now()->startOfWeek()->addDays($index);

            $donorsInDay = Donation::whereDate('created_at', $dayDate)->count();
            $requestsInDay = BloodRequest::whereDate('created_at', $dayDate)->count();

            $weeklyActivity[] = [
                'dayKey'   => $dayKey,
                'donors'   => $donorsInDay,
                'requests' => $requestsInDay
            ];
        }

        // 7. النشاطات الأخيرة المباشرة
        $latestRequests = BloodRequest::with(['hospital', 'bloodType'])->latest()->take(4)->get();
        $recentActivities = $latestRequests->map(function ($act) {
            return [
                'id'       => $act->id,
                'title'    => 'طلب طارئ لفصيلة ' . ($act->bloodType->name ?? ''),
                'subtitle' => $act->hospital->name ?? $act->hospital->facility_name ?? 'مستشفى غير محدد',
                'time'     => $act->created_at ? $act->created_at->diffForHumans() : 'الآن'
            ];
        })->toArray();

        return $this->successResponse([
            'stats'              => $stats,
            'blood_distribution' => $bloodDistribution,
            'radar_points'       => $radarPoints,
            'emergency_trend'    => $emergencyTrend,
            'weekly_activity'    => $weeklyActivity,
            'recent_activities'  => $recentActivities
        ], 'تم جلب بيانات لوحة تحكم الإدارة الحقيقية بنجاح');
    }

    /**
     * دالة Polling سريعة لتحديث إحصائيات لوحة التحكم لحظياً
     */
    public function liveDashboardStats()
    {
        $criticalCount = BloodRequest::whereIn('status', ['pending', 'searching', 'open'])
            ->where(function ($query) {
                $query->where('emergency_level', 'critical')
                      ->orWhere('emergency_level', 'high');
            })
            ->count();

        if ($criticalCount === 0) {
            $criticalCount = BloodRequest::whereIn('status', ['pending', 'searching', 'open'])->count();
        }

        return $this->successResponse([
            'donors_count'    => Donor::count(),
            'hospitals_count' => Hospital::count(),
            'total_requests'  => BloodRequest::count(),
            'total_donations' => Donation::count(),
            'critical_cases'  => $criticalCount,
            'timestamp'       => now()->toDateTimeString()
        ], 'تم تحديث الإحصائيات اللحظية للوحة التحكم');
    }

    /**
     * دالة Polling لملخص التحديثات الحية
     */
    public function livePollingFeed(Request $request)
    {
        try {
            $criticalCount = BloodRequest::whereIn('status', ['pending', 'searching', 'open'])
                ->where(function ($query) {
                    $query->where('emergency_level', 'critical')
                          ->orWhere('emergency_level', 'high');
                })
                ->count();

            $unreadNotifications = 0;
            $user = $request->user();
            if ($user && method_exists($user, 'unreadNotifications')) {
                $unreadNotifications = $user->unreadNotifications()->count();
            }

            return $this->successResponse([
                'critical_cases'       => $criticalCount,
                'unread_notifications' => $unreadNotifications,
                'timestamp'            => now()->toDateTimeString()
            ], 'تم جلب التحديثات الحية للإدارة بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
