<?php

namespace App\Http\Controllers\Donor;

use App\Models\BloodRequest;
use App\Models\DonorResponse;
use App\Repositories\EmergencyRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmergencyNotificationsController extends Controller
{
    use ApiResponseTrait;

    protected $emergencyRepo;

    public function __construct(EmergencyRepository $emergencyRepo)
    {
        $this->emergencyRepo = $emergencyRepo;
    }

    /**
     * جلب قائمة بنداءات الطوارئ النشطة والمفتوحة
     */
    public function index(Request $request)
    {
        $emergencies = $this->emergencyRepo->getActiveEmergencies();
        return $this->successResponse($emergencies, 'تم جلب نداءات الطوارئ المتاحة بنجاح');
    }

    /**
     * قبول طلب الطوارئ وإنشاء سجل استجابة جديد
     */
    public function accept(Request $request, $id)
    {
        $donor = $request->user()->donor ?? null;

        if (!$donor) {
            return $this->notFoundResponse('حساب المستخدم الحالي ليس مسجلاً كمتبرع');
        }

        $bloodRequest = BloodRequest::find($id);

        if (!$bloodRequest) {
            return $this->notFoundResponse('طلب الطوارئ غير موجود');
        }

        // إنشاء أو تحديث سجل الاستجابة للمتبرع بالعمود الصحيح blood_request_id
        $response = DonorResponse::updateOrCreate(
            [
                'donor_id' => $donor->id,
                'blood_request_id' => $bloodRequest->id,
            ],
            [
                'status' => 'accepted',
                'responded_at' => now(),
            ]
        );

        return $this->successResponse($response, 'شكراً لبطولتك! تم تسجيل قبولك للطلب وإبلاغ المستشفى بنجاح');
    }

    /**
     * تحديث حالة الاستجابة مع تأمين صلاحية وحماية السجل
     */
    public function update(Request $request, $id)
    {
        $donor = $request->user()->donor ?? null;

        if (!$donor) {
            return $this->notFoundResponse('حساب المستخدم الحالي ليس مسجلاً كمتبرع');
        }

        // البحث بالمعرف المباشر أو برقم طلب الطوارئ blood_request_id
        $donorResponse = DonorResponse::where('donor_id', $donor->id)
            ->where(function ($query) use ($id) {
                $query->where('id', $id)
                      ->orWhere('blood_request_id', $id);
            })->first();

        $status = $request->input('status', 'accepted');

        if (!$donorResponse) {
            $bloodRequest = BloodRequest::find($id);
            if (!$bloodRequest) {
                return $this->notFoundResponse('سجل الاستجابة أو طلب الطوارئ غير موجود');
            }

            $donorResponse = DonorResponse::create([
                'donor_id' => $donor->id,
                'blood_request_id' => $bloodRequest->id,
                'status' => $status,
                'responded_at' => now(),
            ]);

            return $this->successResponse($donorResponse, 'تم تسجيل استجابتك بنجاح');
        }

        $donorResponse->update([
            'status' => $status,
            'updated_at' => now(),
        ]);

        return $this->successResponse($donorResponse, 'تم تسجيل استجابتك، شكراً لتعاونك!');
    }
}
