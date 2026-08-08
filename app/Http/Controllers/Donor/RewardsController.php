<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller; // استخدام الكنترولر الأساسي بشكل قياسي
use App\Services\RewardService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

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
        $donor = $request->user()->donor;

        // التحقق من وجود حساب متبرع
        if (!$donor) {
            return $this->notFoundResponse('بيانات المتبرع غير موجودة');
        }

        $data = [
            'total_points' => $this->rewardService->getDonorPoints($donor->id),
            'history' => $this->rewardService->getDonorHistory($donor->id),
            'available_badges' => $this->rewardService->getAvailableBadges()
        ];

        return $this->successResponse($data, 'تم جلب بيانات المكافآت والنقاط بنجاح');
    }
}
