<?php

namespace App\Http\Controllers\Donor;

use App\Services\QRCardService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class QRCardController extends Controller
{
    use ApiResponseTrait;

    protected $qrCardService;

    public function __construct(QRCardService $qrCardService)
    {
        $this->qrCardService = $qrCardService;
    }

    public function show(Request $request)
    {
        $donorId = $request->user()->donor->id;
        
        $cardData = $this->qrCardService->generateDonorCard($donorId);
        
        return $this->successResponse($cardData, 'تم توليد بيانات البطاقة الذكية بنجاح');
    }
}