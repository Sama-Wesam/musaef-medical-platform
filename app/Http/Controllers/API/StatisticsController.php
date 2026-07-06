<?php

namespace App\Http\Controllers\API;

use App\Services\StatisticsService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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

        if ($user->role === 'donor' && $user->donor) {
            $stats = $this->statsService->getDonorDashboardStats($user->donor->id);
        } elseif ($user->role === 'hospital' && $user->hospital) {
            $stats = $this->statsService->getHospitalDashboardStats($user->hospital->id);
        }

        return $this->successResponse($stats);
    }
}