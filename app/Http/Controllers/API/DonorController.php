<?php

namespace App\Http\Controllers\API;

use App\Services\QRCardService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DonorController extends Controller
{
    use ApiResponseTrait;

    protected $qrCardService;

    public function __construct(QRCardService $qrCardService)
    {
        $this->qrCardService = $qrCardService;
    }

    public function profile(Request $request)
    {
        $donor = $request->user()->donor()->with('bloodType', 'healthInfo')->first();
        if (!$donor) return $this->notFoundResponse('بيانات المتبرع غير مكتملة');

        return $this->successResponse($donor);
    }

    public function qrCard(Request $request)
    {
        $donorId = $request->user()->donor->id ?? null;
        if (!$donorId) return $this->notFoundResponse();

        $cardData = $this->qrCardService->generateDonorCard($donorId);
        return $this->successResponse($cardData, 'تم جلب بيانات البطاقة الذكية');
    }
}