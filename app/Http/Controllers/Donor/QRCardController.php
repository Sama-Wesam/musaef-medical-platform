<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class QRCardController extends Controller
{
    use ApiResponseTrait;

    /**
     * عرض بيانات بطاقة المتبرع الرقمية ورابط الـ QR Code
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $donor = $user->donor;

        // التحقق من وجود حساب متبرع مرتبط للمستخدم
        if (!$donor) {
            return $this->notFoundResponse('بيانات المتبرع غير موجودة لهذا المستخدم');
        }

        // تحميل علاقة فصيلة الدم
        $donor->load('bloodType');

        $qrData = [
            'card_id' => 'DONOR-' . str_pad($donor->id, 6, '0', STR_PAD_LEFT),
            'donor_name' => $user->name,
            'blood_type' => $donor->bloodType->name ?? 'غير محدد',
            'phone' => $donor->phone ?? $user->phone,
            'qr_code_data' => [
                'donor_id' => $donor->id,
                'user_id' => $user->id,
                'verified' => true
            ],
            'status' => 'active'
        ];

        return $this->successResponse($qrData, 'تم جلب بيانات بطاقة المتبرع بنجاح');
    }
}
