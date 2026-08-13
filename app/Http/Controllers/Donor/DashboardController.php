<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StatisticsService;
use App\Traits\ApiResponseTrait;
use App\AI\EmergencyPriorityEngine;
use App\Models\BloodRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    protected $statsService;

    public function __construct(StatisticsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * جلب إحصائيات لوحة تحكم المتبرع الحقيقية
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $donor = $user ? ($user->donor ?? null) : null;

            $donationsCount = 0;
            $userPoints = 0;
            $daysRemaining = 0;
            $lastDonationDate = null;
            $lastDonationText = 'لا توجد تبرعات مسجلة';

            if ($donor) {
                $donationsCount = DB::table('donations')
                    ->where('donor_id', $donor->id)
                    ->where('status', 'completed')
                    ->count();

                $userPoints = $user->points ?? ($donor->points ?? 0);

                // جلب تاريخ آخر تبرع من عدة مصادر بالترتيب
                $healthInfo = DB::table('health_infos')->where('donor_id', $donor->id)->first();

                if ($healthInfo && !empty($healthInfo->last_donation_date)) {
                    $lastDonationDate = $healthInfo->last_donation_date;
                } elseif (!empty($donor->last_donation_date)) {
                    $lastDonationDate = $donor->last_donation_date;
                } else {
                    $latestDonation = DB::table('donations')
                        ->where('donor_id', $donor->id)
                        ->where('status', 'completed')
                        ->orderBy('donation_date', 'desc')
                        ->first();

                    if ($latestDonation) {
                        $lastDonationDate = $latestDonation->donation_date;
                    }
                }

                if ($lastDonationDate) {
                    $lastDateCarbon = Carbon::parse($lastDonationDate);
                    $nextDonationDate = $lastDateCarbon->copy()->addDays(90); // دورة التعافي 90 يوماً
                    $now = Carbon::now();

                    if ($now->gte($nextDonationDate)) {
                        $daysRemaining = 0;
                        $lastDonationText = 'يمكنك التبرع الآن';
                    } else {
                        $daysRemaining = (int) $now->diffInDays($nextDonationDate);
                        $daysPassed = (int) $lastDateCarbon->diffInDays($now);
                        $lastDonationText = "آخر تبرع منذ {$daysPassed} يوم";
                    }
                }
            }

            $nearbyRequestsCount = DB::table('blood_requests')
                ->whereIn('status', ['open', 'active', 'searching', 'pending'])
                ->count();

            $isEligible = true;
            if ($donor) {
                $isEligible = isset($donor->is_eligible) ? (bool)$donor->is_eligible : ($daysRemaining === 0);
            }

            $realTimeStats = [
                'donations_count'          => $donationsCount,
                'points'                   => $userPoints,
                'badges_count'             => (int) floor($userPoints / 50),
                'days_until_next_donation' => $daysRemaining,
                'is_eligible'              => $isEligible,
                'last_donation_text'       => $lastDonationText,
                'level'                    => $userPoints >= 200 ? 'متقدم' : 'مبتدئ',
                'nearby_requests_count'    => $nearbyRequestsCount
            ];

            return $this->successResponse($realTimeStats, 'تم جلب الإحصائيات الحقيقية بنجاح');

        } catch (Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء جلب البيانات: ' . $e->getMessage(), 500);
        }
    }

    /**
     * ⚡ دالة Polling سريعة جداً للوحة تحكم المتبرع
     */
    public function liveStatsPolling(Request $request)
    {
        try {
            $user = $request->user();
            $donor = $user ? ($user->donor ?? null) : null;

            $donationsCount = $donor
                ? DB::table('donations')->where('donor_id', $donor->id)->where('status', 'completed')->count()
                : 0;

            $userPoints = $user->points ?? ($donor->points ?? 0);

            $openRequests = DB::table('blood_requests')
                ->whereIn('status', ['open', 'active', 'searching', 'pending'])
                ->count();

            return $this->successResponse([
                'donations_count'       => $donationsCount,
                'points'                => $userPoints,
                'nearby_requests_count' => $openRequests,
                'timestamp'             => now()->toDateTimeString()
            ], 'تم التحديث المباشر بنجاح');

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * ⚡ دالة الـ Polling الخاصة بنداءات وإشعارات المتبرع الحية
     */
    public function livePollingAlerts(Request $request)
    {
        try {
            $user = $request->user();
            $unreadNotifications = ($user && method_exists($user, 'unreadNotifications'))
                ? $user->unreadNotifications()->count()
                : 0;

            $activeEmergenciesCount = BloodRequest::whereIn('status', ['searching', 'active', 'pending', 'open'])->count();

            return $this->successResponse([
                'unread_notifications' => $unreadNotifications,
                'active_emergencies'   => $activeEmergenciesCount,
                'timestamp'            => now()->toDateTimeString()
            ], 'تم جلب تنبيهات المتبرع الحية بنجاح');

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * جلب الطلبات العاجلة للمتبرع وتنسيق القيم لتفادي طباعة كائنات الـ JSON
     */
    public function getUrgentRequests(Request $request, EmergencyPriorityEngine $priorityEngine)
    {
        try {
            $rawRequests = DB::table('blood_requests')
                ->leftJoin('hospitals', 'blood_requests.hospital_id', '=', 'hospitals.id')
                ->leftJoin('blood_types', 'blood_requests.blood_type_id', '=', 'blood_types.id')
                ->select(
                    'blood_requests.id',
                    'blood_requests.units_required',
                    'blood_requests.emergency_level',
                    'blood_requests.created_at',
                    'hospitals.facility_name',
                    'hospitals.address',
                    'blood_types.name as blood_type_name'
                )
                ->whereIn('blood_requests.status', ['open', 'active', 'searching', 'pending'])
                ->orderBy('blood_requests.created_at', 'desc')
                ->take(10)
                ->get();

            if ($rawRequests->isEmpty()) {
                return $this->successResponse([], 'لا توجد حالات طارئة مفتوحة حالياً');
            }

            $formatted = $rawRequests->map(function ($req) {
                // استخراج اسم الفصيلة بشكل نصي نقي
                $bloodName = $req->blood_type_name ?? 'O+';
                if (is_string($bloodName) && str_starts_with(trim($bloodName), '{')) {
                    $decoded = json_decode($bloodName, true);
                    $bloodName = $decoded['name'] ?? 'O+';
                }

                return [
                    'id'             => $req->id,
                    'hospital_name'  => $req->facility_name ?? 'مجمع الشفاء الطبي',
                    'location'       => $req->address ?? 'غزة - الرمال',
                    'blood_type'     => (string) $bloodName,
                    'units_needed'   => (int) ($req->units_required ?? 1),
                    'condition_type' => 'حالة طارئة عاجلة',
                    'severity'       => ucfirst($req->emergency_level ?? 'Critical'),
                    'created_at'     => $req->created_at ? Carbon::parse($req->created_at)->diffForHumans() : 'منذ قليل'
                ];
            })->toArray();

            try {
                $sorted = $priorityEngine->sortRequests($formatted);
                return $this->successResponse($sorted, 'تم ترتيب وجلب الحالات العاجلة بنجاح');
            } catch (Exception $e) {
                return $this->successResponse($formatted, 'تم جلب الحالات العاجلة بنجاح');
            }

        } catch (Exception $e) {
            return $this->errorResponse('خطأ أثناء استعلام الحالات: ' . $e->getMessage(), 500);
        }
    }
}
