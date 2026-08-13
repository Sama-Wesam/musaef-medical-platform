<?php

namespace App\Http\Controllers\API;

use App\Services\StatisticsService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class StatisticsController extends Controller
{
    use ApiResponseTrait;

    protected $statsService;

    public function __construct(StatisticsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * جلب إحصائيات لوحة المستخدم (العادية - تستخدم الكاش للتخفيف)
     */
    public function userStats(Request $request)
    {
        $user = $request->user();

        $cacheKey = "user_stats_{$user->role}_{$user->id}";

        $stats = Cache::remember($cacheKey, 60, function () use ($user) {
            if ($user->role === 'donor' && $user->donor) {
                return $this->statsService->getDonorDashboardStats($user->donor->id);
            } elseif ($user->role === 'hospital' && $user->hospital) {
                return $this->statsService->getHospitalDashboardStats($user->hospital->id);
            }
            return [];
        });

        return $this->successResponse($stats, 'تم جلب إحصائيات المستخدم بنجاح');
    }

    /**
     * ⚡ دالة الـ Polling المباشرة الفورية لتحديث الإحصائيات لحظياً
     */
    public function liveStatsPolling(Request $request)
    {
        $user = $request->user();
        $stats = [];

        // إرجاع أحدث بيانات دقيقة ومباشرة من قواعد البيانات فوراً للـ Polling
        if ($user->role === 'donor' && $user->donor) {
            $stats = $this->statsService->getDonorDashboardStats($user->donor->id);
        } elseif ($user->role === 'hospital' && $user->hospital) {
            $stats = $this->statsService->getHospitalDashboardStats($user->hospital->id);
        }

        // تحديث الكاش بالبيانات الجديدة مباشرة
        $cacheKey = "user_stats_{$user->role}_{$user->id}";
        Cache::put($cacheKey, $stats, 60);

        return $this->successResponse([
            'stats'     => $stats,
            'timestamp' => now()->toDateTimeString()
        ], 'تم تحديث الإحصائيات حركياً بنجاح');
    }
}
