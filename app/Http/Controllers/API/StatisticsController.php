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

    public function userStats(Request $request)
    {
        $user = $request->user();
        $stats = [];

        // إنشاء مفتاح كاش فريد لكل مستخدم بناءً على معرفه ودوره
        $cacheKey = "user_stats_{$user->role}_{$user->id}";

        // استخدام Cache::remember لحفظ وجلب البيانات تلقائياً لمدة 600 ثانية (10 دقائق)
        $stats = Cache::remember($cacheKey, 600, function () use ($user) {
            if ($user->role === 'donor' && $user->donor) {
                return $this->statsService->getDonorDashboardStats($user->donor->id);
            } elseif ($user->role === 'hospital' && $user->hospital) {
                return $this->statsService->getHospitalDashboardStats($user->hospital->id);
            }
            return [];
        });

        return $this->successResponse($stats);
    }
}
