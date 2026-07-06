<?php

namespace App\Http\Controllers\Donor;

use App\Services\StatisticsService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    protected $statsService;

    public function __construct(StatisticsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * جلب إحصائيات لوحة تحكم المتبرع
     */
    public function index(Request $request)
    {
        $donorId = $request->user()->donor->id;
        $stats = $this->statsService->getDonorDashboardStats($donorId);
        
        return $this->successResponse($stats, 'تم جلب إحصائيات لوحة التحكم بنجاح');
    }
}