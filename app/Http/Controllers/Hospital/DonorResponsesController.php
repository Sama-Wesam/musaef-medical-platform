<?php

namespace App\Http\Controllers\Hospital;

use App\Models\DonorResponse;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DonorResponsesController extends Controller
{
    use ApiResponseTrait;

    /**
     * جلب المتبرعين الذين استجابوا لطلب محدد
     */
    public function index(Request $request, $requestId)
    {
        $hospital = $request->user()->hospital;
        if (!$hospital) {
            return $this->notFoundResponse('حساب المستخدم الحالي غير مرتبط بجهة طبية');
        }

        $hospitalId = $hospital->id;

        $responses = DonorResponse::with(['donor.user', 'donor.bloodType'])
            ->whereHas('bloodRequest', function($query) use ($hospitalId, $requestId) {
                $query->where('hospital_id', $hospitalId)
                      ->where('id', $requestId);
            })
            ->where('status', 'accepted')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse($responses, 'تم جلب المتبرعين المستجيبين للطلب');
    }

    /**
     * ⚡ دالة Polling حية لمراقبة استجابات المتبرعين الجدد لطلب دم معين
     */
    public function livePollResponses(Request $request, $requestId)
    {
        $hospital = $request->user()->hospital;
        if (!$hospital) {
            return $this->notFoundResponse('حساب المستخدم الحالي غير مرتبط بجهة طبية');
        }

        $hospitalId = $hospital->id;

        $responses = DonorResponse::with(['donor.user:id,name', 'donor.bloodType:id,name'])
            ->whereHas('bloodRequest', function($query) use ($hospitalId, $requestId) {
                $query->where('hospital_id', $hospitalId)->where('id', $requestId);
            })
            ->where('status', 'accepted')
            ->latest()
            ->get();

        return $this->successResponse([
            'responses_count' => $responses->count(),
            'responders'      => $responses,
            'timestamp'       => now()->toDateTimeString()
        ], 'تم جلب الاستجابات الحية بنجاح');
    }
}
