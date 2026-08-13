<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use App\AI\BloodDemandForecast;
use App\Models\BloodRequest;
use App\Models\BloodInventory;
use App\Models\Donation;
use App\Models\Notification;

class DashboardController extends Controller
{
    /**
     * جلب إحصائيات لوحة تحكم المستشفى الحقيقية من قاعدة البيانات
     */
    public function index(Request $request, BloodDemandForecast $demandForecast)
    {
        try {
            $user = $request->user();
            $hospital = $user ? $user->hospital : null;
            $hospitalId = $hospital ? $hospital->id : null;

            if (!$hospitalId) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'حساب المستخدم الحالي غير مرتبط بجهة طبية موثقة'
                ], 401);
            }

            $inventoryTableName = null;
            if (Schema::hasTable('blood_inventories')) {
                $inventoryTableName = 'blood_inventories';
            } elseif (Schema::hasTable('inventories')) {
                $inventoryTableName = 'inventories';
            } elseif (Schema::hasTable('hospital_inventories')) {
                $inventoryTableName = 'hospital_inventories';
            }

            $inventoryData = collect();
            if ($inventoryTableName) {
                $hasMinThreshold = Schema::hasColumn($inventoryTableName, 'min_threshold');
                $hasMinLimit = Schema::hasColumn($inventoryTableName, 'min_limit');

                if (Schema::hasTable('blood_types')) {
                    $selectColumns = [
                        'blood_types.name as blood_type',
                        "{$inventoryTableName}.units_available"
                    ];

                    if ($hasMinThreshold) {
                        $selectColumns[] = "{$inventoryTableName}.min_threshold";
                    } elseif ($hasMinLimit) {
                        $selectColumns[] = "{$inventoryTableName}.min_limit as min_threshold";
                    }

                    $inventoryData = DB::table($inventoryTableName)
                        ->join('blood_types', "{$inventoryTableName}.blood_type_id", '=', 'blood_types.id')
                        ->where("{$inventoryTableName}.hospital_id", $hospitalId)
                        ->select($selectColumns)
                        ->get();
                } else {
                    $inventoryData = DB::table($inventoryTableName)
                        ->where('hospital_id', $hospitalId)
                        ->get();
                }
            }

            $allBloodTypes = ['O+', 'A+', 'B+', 'AB+', 'O-', 'A-', 'B-', 'AB-'];
            $bloodDistribution = [];
            $inventoryAlerts = [];
            $totalUnitsAvailable = 0;

            foreach ($allBloodTypes as $type) {
                $found = $inventoryData->firstWhere('blood_type', $type);
                $units = $found->units_available ?? 0;
                $minThreshold = isset($found->min_threshold) ? $found->min_threshold : 5;

                $totalUnitsAvailable += $units;

                $bloodDistribution[] = [
                    'name'  => $type,
                    'units' => (int) $units
                ];

                if ($units <= 3 && $units > 0) {
                    $inventoryAlerts[] = [
                        'blood_type' => $type,
                        'status'     => 'critical_low',
                        'units'      => (int) $units
                    ];
                } elseif ($units == 0) {
                    $inventoryAlerts[] = [
                        'blood_type' => $type,
                        'status'     => 'critical',
                        'units'      => (int) $units
                    ];
                } elseif ($units < $minThreshold) {
                    $inventoryAlerts[] = [
                        'blood_type' => $type,
                        'status'     => 'low',
                        'units'      => (int) $units
                    ];
                }
            }

            $activeRequestsCount = 0;
            $criticalCases = 0;

            if (Schema::hasTable('blood_requests')) {
                $hasUrgencyLevel   = Schema::hasColumn('blood_requests', 'urgency_level');
                $hasEmergencyLevel = Schema::hasColumn('blood_requests', 'emergency_level');
                $hasUrgency        = Schema::hasColumn('blood_requests', 'urgency');
                $hasPriority       = Schema::hasColumn('blood_requests', 'priority');
                $hasIsEmergency    = Schema::hasColumn('blood_requests', 'is_emergency');

                $activeRequestsQuery = DB::table('blood_requests')
                    ->where('hospital_id', $hospitalId)
                    ->whereIn('status', ['active', 'pending', 'قيد المعالجة', 'مفتوح', 'searching', 'open', 'accepted']);

                $activeRequestsCount = $activeRequestsQuery->count();

                $criticalQuery = clone $activeRequestsQuery;

                if ($hasUrgencyLevel || $hasEmergencyLevel || $hasUrgency || $hasPriority || $hasIsEmergency) {
                    $criticalCases = $criticalQuery->where(function ($q) use ($hasUrgencyLevel, $hasEmergencyLevel, $hasUrgency, $hasPriority, $hasIsEmergency) {
                        if ($hasUrgencyLevel) {
                            $q->orWhere('urgency_level', 'critical')->orWhere('urgency_level', 'حرج');
                        }
                        if ($hasEmergencyLevel) {
                            $q->orWhere('emergency_level', 'critical')->orWhere('emergency_level', 'حرج');
                        }
                        if ($hasUrgency) {
                            $q->orWhere('urgency', 'critical')->orWhere('urgency', 'حرج');
                        }
                        if ($hasPriority) {
                            $q->orWhere('priority', 'critical')->orWhere('priority', 'حرج')->orWhere('priority', 'high')->orWhere('priority', 'عالي');
                        }
                        if ($hasIsEmergency) {
                            $q->orWhere('is_emergency', 1)->orWhere('is_emergency', true);
                        }
                    })->count();
                }
            }

            $todayDonors = 0;
            if (Schema::hasTable('donations')) {
                $todayDonors = DB::table('donations')
                    ->where('hospital_id', $hospitalId)
                    ->whereDate('created_at', now()->today())
                    ->count();
            }

            $monthlyRequests = [];
            $monthsMap = [
                1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr',
                5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'aug',
                9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dec'
            ];

            if (Schema::hasTable('blood_requests')) {
                $monthlyData = DB::table('blood_requests')
                    ->where('hospital_id', $hospitalId)
                    ->select(DB::raw('MONTH(created_at) as month_num'), DB::raw('COUNT(*) as total'))
                    ->groupBy('month_num')
                    ->pluck('total', 'month_num')
                    ->toArray();

                foreach ($monthsMap as $num => $key) {
                    $monthlyRequests[] = [
                        'key'   => $key,
                        'month' => $key,
                        'count' => $monthlyData[$num] ?? 0
                    ];
                }
            }

            $lowestInventory = collect($bloodDistribution)->sortBy('units')->first();
            $targetBloodType = $lowestInventory ? $lowestInventory['name'] : 'O-';
            $targetUnits = $lowestInventory ? $lowestInventory['units'] : 0;

            $aiPrediction = [];
            if ($demandForecast) {
                try {
                    $aiPrediction = $demandForecast->predictShortage(
                        $targetBloodType,
                        $targetUnits,
                        2,
                        $activeRequestsCount,
                        $criticalCases > 0,
                        (int) (floor((now()->month % 12) / 3) + 1)
                    );
                } catch (\Throwable $e) {
                    $aiPrediction = [
                        'blood_type'       => $targetBloodType,
                        'predicted_demand' => $activeRequestsCount * 2,
                        'current_units'    => $targetUnits,
                        'shortage_risk'    => $targetUnits < 5 ? 'high' : 'normal',
                        'recommendation'   => 'تحديث نظام التنبؤ بالطلب جارٍ بشكل تلقائي'
                    ];
                }
            }

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'stats' => [
                        'critical_cases'       => $criticalCases,
                        'critical_cases_text'  => 'active_active_cases',
                        'available_units'      => $totalUnitsAvailable,
                        'available_units_text' => 'total_available_units',
                        'today_donors'         => $todayDonors,
                        'today_donors_text'    => 'registered_today',
                        'active_requests'      => $activeRequestsCount,
                        'active_requests_text' => 'under_followup'
                    ],
                    'blood_distribution' => $bloodDistribution,
                    'monthly_requests'   => $monthlyRequests,
                    'inventory_alerts'   => $inventoryAlerts,
                    'ai_prediction'      => $aiPrediction
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'حدث خطأ أثناء تحميل بيانات لوحة التحكم: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * دالة Polling سريعة وخفيفة جداً لتحديث إحصائيات لوحة تحكم المستشفى
     */
    public function liveDashboardStats(Request $request)
    {
        try {
            $user = $request->user();
            $hospital = $user ? $user->hospital : null;
            $hospitalId = $hospital ? $hospital->id : null;

            if (!$hospitalId) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized facility'], 401);
            }

            $activeCount = Schema::hasTable('blood_requests') ? DB::table('blood_requests')
                ->where('hospital_id', $hospitalId)
                ->whereIn('status', ['active', 'pending', 'searching', 'open', 'accepted'])
                ->count() : 0;

            $todayDonors = Schema::hasTable('donations') ? DB::table('donations')
                ->where('hospital_id', $hospitalId)
                ->whereDate('created_at', now()->today())
                ->count() : 0;

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'active_requests' => $activeCount,
                    'today_donors'    => $todayDonors,
                    'timestamp'       => now()->toDateTimeString()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * دالة الـ Polling السريعة والمحسنة بمعمارية Cache لتخفيف الضغط على الخادم
     */
    public function livePollingUpdates(Request $request)
    {
        try {
            $user = $request->user();
            $hospital = $user ? $user->hospital : null;

            if (!$hospital) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized facility'], 401);
            }

            $cacheKey = "hospital_polling_updates_{$hospital->id}";

            $data = Cache::remember($cacheKey, 5, function () use ($hospital) {
                $activeRequests = BloodRequest::where('hospital_id', $hospital->id)
                    ->whereIn('status', ['active', 'pending', 'searching', 'open'])
                    ->select('id', 'status', 'units_required', 'updated_at')
                    ->get();

                $unreadNotifications = Notification::where('user_id', $hospital->user_id)
                    ->where('is_read', false)
                    ->count();

                return [
                    'active_requests_count' => $activeRequests->count(),
                    'active_requests'       => $activeRequests,
                    'unread_notifications'  => $unreadNotifications,
                    'timestamp'             => now()->toDateTimeString()
                ];
            });

            return response()->json([
                'status' => 'success',
                'data'   => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * إرجاع تقرير الذكاء الاصطناعي المباشر
     */
    public function aiForecastReport(Request $request, BloodDemandForecast $demandForecast)
    {
        try {
            $user = $request->user();
            $hospital = $user ? $user->hospital : null;

            if (!$hospital) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            }

            $inventory = DB::table('blood_inventories')
                ->join('blood_types', 'blood_inventories.blood_type_id', '=', 'blood_types.id')
                ->where('blood_inventories.hospital_id', $hospital->id)
                ->where('blood_types.name', 'O-')
                ->first();

            $units = $inventory ? $inventory->units_available : 0;

            $report = $demandForecast->predictShortage('O-', $units, 2, 1, true, 1);

            return response()->json([
                'success' => true,
                'data'    => $report
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
