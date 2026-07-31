<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateBloodRequest;
use App\Services\EmergencyService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmergencyController extends Controller
{
    use ApiResponseTrait;

    protected $emergencyService;

    public function __construct(EmergencyService $emergencyService)
    {
        $this->emergencyService = $emergencyService;
    }

    public function store(CreateBloodRequest $request)
    {
        // يجب أن يكون المستخدم مستشفى للقيام بهذا
        $hospital = $request->user()->hospital ?? null;

        if (!$hospital || !$hospital->is_verified) {
            return $this->unauthorizedResponse('فقط المستشفيات الموثقة يمكنها إنشاء نداء طوارئ.');
        }

        try {
            $emergency = $this->emergencyService->createEmergencyRequest($request->validated(), $hospital);
            return $this->successResponse($emergency, 'تم إطلاق نداء الطوارئ بنجاح، جاري البحث عن متبرعين.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function resolve(Request $request, $id)
    {
        $success = $this->emergencyService->markAsCompleted($id);
        if ($success) {
            return $this->successResponse(null, 'تم إغلاق حالة الطوارئ بنجاح.');
        }
        return $this->errorResponse('لا يمكن إغلاق هذه الحالة أو أنها غير موجودة.');
    }
}
