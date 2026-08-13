<?php

namespace App\Http\Controllers\Donor;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorResponse;
use App\Repositories\EmergencyRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Exception;

class EmergencyNotificationsController extends Controller
{
    use ApiResponseTrait;

    protected $emergencyRepo;

    public function __construct(EmergencyRepository $emergencyRepo)
    {
        $this->emergencyRepo = $emergencyRepo;
    }

    public function index(Request $request)
    {
        try {
            $emergencies = $this->emergencyRepo->getActiveEmergencies();
            return $this->successResponse($emergencies, 'تم جلب نداءات الطوارئ المتاحة بنجاح');
        } catch (Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء جلب نداءات الطوارئ: ' . $e->getMessage(), 500);
        }
    }

    /**
     * ⚡ دالة Polling سريعة لمعرفة وجود حالات طوارئ حرجة جديدة
     */
    public function pollActiveEmergencies(Request $request)
    {
        try {
            $emergencies = $this->emergencyRepo->getActiveEmergencies();
            return $this->successResponse([
                'count'     => count($emergencies),
                'data'      => $emergencies,
                'timestamp' => now()->toDateTimeString()
            ], 'تم التحديث المباشر لحالات الطوارئ');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function accept(Request $request, $id)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->unauthorizedResponse('يرجى تسجيل الدخول أولاً');
            }

            $donor = $user->donor ?? $user->donorProfile ?? Donor::where('user_id', $user->id)->first();

            if (!$donor) {
                return $this->notFoundResponse('حساب المستخدم الحالي ليس مسجلاً كمتبرع');
            }

            $bloodRequest = BloodRequest::find($id);

            if (!$bloodRequest) {
                return $this->notFoundResponse('طلب الطوارئ غير موجود');
            }

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

        } catch (Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء تسجيل القبول: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->unauthorizedResponse('يرجى تسجيل الدخول أولاً');
            }

            $donor = $user->donor ?? $user->donorProfile ?? Donor::where('user_id', $user->id)->first();

            if (!$donor) {
                return $this->notFoundResponse('حساب المستخدم الحالي ليس مسجلاً كمتبرع');
            }

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

        } catch (Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء تحديث حالة الاستجابة: ' . $e->getMessage(), 500);
        }
    }
}
