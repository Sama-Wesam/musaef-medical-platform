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
        // 1. حماية من الأخطاء في حال لم يكن المستخدم متبرعاً
        $donor = $request->user()->donor;

        if (!$donor) {
            // افتراض وجود دالة errorResponse في الـ Trait الخاص بك
            return $this->errorResponse('لم يتم العثور على حساب متبرع مرتبط بهذا المستخدم', 404);
        }

        // 2. تمرير الـ ID بأمان
        $cardData = $this->qrCardService->generateDonorCard($donor->id);

        if (!$cardData) {
            return $this->errorResponse('حدث خطأ أثناء جلب بيانات البطاقة', 400);
        }

        return $this->successResponse($cardData, 'تم توليد بيانات البطاقة الذكية بنجاح');
    }
}
