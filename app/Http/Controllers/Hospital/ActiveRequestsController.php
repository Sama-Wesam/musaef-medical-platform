<?php

namespace App\Http\Controllers\Hospital;

use App\Models\BloodRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ActiveRequestsController extends Controller
{
    use ApiResponseTrait;

    /**
     * جلب الطلبات النشطة فقط
     */
    public function index(Request $request)
    {
        $hospital = $request->user()->hospital;
        if (!$hospital) {
            return $this->notFoundResponse('حساب المستخدم الحالي غير مرتبط بجهة طبية');
        }

        $hospitalId = $hospital->id;

        $activeRequests = BloodRequest::with('bloodType', 'responses')
            ->where('hospital_id', $hospitalId)
            ->whereIn('status', ['pending', 'searching', 'accepted', 'active', 'open'])
            ->orderBy('emergency_level', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse($activeRequests, 'تم جلب الطلبات النشطة');
    }

    /**
     * ⚡ دالة Polling حية للطلبات النشطة للمستشفى
     */
    public function livePollActiveRequests(Request $request)
    {
        $hospital = $request->user()->hospital;
        if (!$hospital) {
            return $this->notFoundResponse('حساب المستخدم الحالي غير مرتبط بجهة طبية');
        }

        $activeRequests = BloodRequest::select('id', 'blood_type_id', 'units_required', 'status', 'created_at')
            ->with('bloodType:id,name')
            ->where('hospital_id', $hospital->id)
            ->whereIn('status', ['pending', 'searching', 'accepted', 'active', 'open'])
            ->latest()
            ->get();

        return $this->successResponse([
            'count'     => $activeRequests->count(),
            'requests'  => $activeRequests,
            'timestamp' => now()->toDateTimeString()
        ], 'تم تحديث الطلبات النشطة للمستشفى بنجاح');
    }
}
