<?php

namespace App\Http\Controllers\API;
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

    public function store(Request $request)
    {
        // يجب أن يكون المستخدم مستشفى للقيام بهذا
        $hospital = $request->user()->hospital;
        
        if (!$hospital || !$hospital->is_verified) {
            return $this->unauthorizedResponse('فقط المستشفيات الموثقة يمكنها إنشاء نداء طوارئ.');
        }

        $validated = $request->validate([
            'blood_type_id' => 'required|exists:blood_types,id',
            'units_required' => 'required|integer|min:1',
            'emergency_level' => 'required|in:normal,high,critical',
        ]);

        try {
            $emergency = $this->emergencyService->createEmergencyRequest($validated, $hospital);
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