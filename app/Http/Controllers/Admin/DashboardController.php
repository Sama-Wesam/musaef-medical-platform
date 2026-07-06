<?php

namespace App\Http\Controllers\Admin;

use App\Services\StatisticsService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * عرض الإحصائيات الرئيسية للوحة تحكم الإدارة
     */
    public function index()
    {
        try {
            $stats = $this->statisticsService->getAdminDashboardStats();
            return $this->successResponse($stats, 'تم جلب الإحصائيات بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء جلب الإحصائيات', 500);
        }
    }
}