<?php

namespace App\Http\Controllers\API;

use App\Services\DonationService;
use App\Models\BloodRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DonationController extends Controller
{
    use ApiResponseTrait;

    protected $donationService;

    public function __construct(DonationService $donationService)
    {
        $this->donationService = $donationService;
    }

    /**
     * يقبل المتبرع نداء الطوارئ
     */
    public function acceptEmergency(Request $request, $requestId)
    {
        try {
            $user = $request->user();
            $donor = $user->donor ?? null;

            if (!$donor) {
                return $this->errorResponse('حساب المستخدم الحالي ليس مسجلاً كمتبرع.', 403);
            }

            $bloodRequest = BloodRequest::findOrFail($requestId);

            // التحقق من توفر الدالة أو تنفيذ التحديث المباشر لحالة الطلب إن لم تكن موجودة في الـ Service
            if (method_exists($this->donationService, 'acceptEmergencyRequest')) {
                $this->donationService->acceptEmergencyRequest($donor, $bloodRequest);
            } else {
                $bloodRequest->update(['status' => 'accepted']);
            }

            return $this->successResponse(null, 'شكراً لبطولتك! تم إبلاغ المستشفى بقدومك.');

        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء قبول الطلب: ' . $e->getMessage(), 500);
        }
    }

    /**
     * المستشفى تؤكد نجاح سحب الدم
     */
    public function store(Request $request)
    {
        // التحقق من صلاحية المستشفى
        $hospitalId = $request->user()->hospital->id ?? null;
        if (!$hospitalId) {
            return $this->unauthorizedResponse('غير مصرح لك بالوصول.');
        }

        // التحقق من البيانات المرسلة
        $validated = $request->validate([
            'donor_id'         => 'required|exists:donors,id',
            'blood_request_id' => 'nullable|exists:blood_requests,id',
            'units_donated'    => 'required|integer|min:1',
            'donation_date'    => 'required|date',
        ]);

        // إضافة معرف المستشفى للبيانات المعتمدة
        $validated['hospital_id'] = $hospitalId;

        // تسجيل عملية التبرع وتحديث المخزون
        $donation = $this->donationService->recordSuccessfulDonation($validated);

        return $this->successResponse($donation, 'تم تسجيل التبرع ومنح النقاط للمتبرع بنجاح.', 201);
    }
}
