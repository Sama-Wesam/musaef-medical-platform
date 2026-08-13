<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\EmergencyRepository;
use App\Models\BloodRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RequestManagementController extends Controller
{
    use ApiResponseTrait;

    protected $emergencyRepo;

    public function __construct(EmergencyRepository $emergencyRepo)
    {
        $this->emergencyRepo = $emergencyRepo;
    }

    public function index()
    {
        $requests = $this->emergencyRepo->getActiveEmergencies();
        return $this->successResponse($requests, 'تم جلب طلبات الطوارئ');
    }

    /**
     * ⚡ دالة Polling خفيفة لمراقبة وتحديث جميع الطلبات على مستعرض المسؤول
     */
    public function livePollRequests()
    {
        $requests = BloodRequest::select('id', 'hospital_id', 'blood_type_id', 'units_required', 'status', 'urgency_level', 'created_at')
            ->whereIn('status', ['pending', 'searching', 'active', 'open'])
            ->latest()
            ->get();

        return $this->successResponse([
            'count'     => $requests->count(),
            'requests'  => $requests,
            'timestamp' => now()->toDateTimeString()
        ], 'تم تحديث قائمة طلبات الطوارئ الشاملة');
    }

    public function show($id)
    {
        $request = $this->emergencyRepo->findById($id);
        if (!$request) return $this->notFoundResponse();

        return $this->successResponse($request);
    }

    public function cancelRequest($id)
    {
        $updated = $this->emergencyRepo->updateStatus($id, 'cancelled');

        if ($updated) {
            return $this->successResponse(null, 'تم إلغاء الطلب إدارياً');
        }

        return $this->errorResponse('فشل في إلغاء الطلب');
    }
}
