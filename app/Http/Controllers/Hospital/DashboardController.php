<?php

namespace App\Http\Controllers\Hospital;

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
     * جلب إحصائيات لوحة تحكم المستشفى
     */
    public function index(Request $request)
    {
        $hospitalId = $request->user()->hospital->id;
        $stats = $this->statsService->getHospitalDashboardStats($hospitalId);
        
        return $this->successResponse($stats, 'تم جلب الإحصائيات بنجاح');
    }
}