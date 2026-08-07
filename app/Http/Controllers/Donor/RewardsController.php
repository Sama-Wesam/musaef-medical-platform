<?php

namespace App\Http\Controllers\Donor;

use App\Services\RewardService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RewardsController extends Controller
{
    use ApiResponseTrait;

    protected $rewardService;

    public function __construct(RewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    public function index(Request $request)
    {
        $donorId = $request->user()->donor->id;
        
        $data = [
            'total_points' => $this->rewardService->getDonorPoints($donorId),
            'history' => $this->rewardService->getDonorHistory($donorId),
            'available_badges' => $this->rewardService->getAvailableBadges()
        ];

        return $this->successResponse($data, 'تم جلب بيانات المكافآت والنقاط');
    }
}