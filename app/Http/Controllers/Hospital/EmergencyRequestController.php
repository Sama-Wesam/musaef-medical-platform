<?php

namespace App\Http\Controllers\Hospital;

use App\Services\EmergencyService;
use App\Repositories\EmergencyRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmergencyRequestController extends Controller
{
    use ApiResponseTrait;

    protected $emergencyService;
    protected $emergencyRepo;

    public function __construct(EmergencyService $emergencyService, EmergencyRepository $emergencyRepo)
    {
        $this->emergencyService = $emergencyService;
        $this->emergencyRepo = $emergencyRepo;
    }

    /**
     * جلب سجل جميع الطلبات التي أنشأها المستشفى
     */
    public function index(Request $request)
    {
        $hospitalId = $request->user()->hospital->id;
        $requests = $this->emergencyRepo->getRequestsByHospital($hospitalId);
        return $this->successResponse($requests, 'تم جلب سجل الطلبات');
    }

    /**
     * إنشاء طلب طوارئ جديد
     */
    public function store(Request $request)
    {
        $hospital = $request->user()->hospital;
        
        if (!$hospital->is_verified) {
            return $this->unauthorizedResponse('عذراً، يجب توثيق حساب المستشفى من قبل الإدارة أولاً لتتمكن من إنشاء طلبات.');
        }

        $validated = $request->validate([
            'blood_type_id' => 'required|exists:blood_types,id',
            'units_required' => 'required|integer|min:1',
            'emergency_level' => 'required|in:normal,high,critical',
        ]);

        try {
            $emergency = $this->emergencyService->createEmergencyRequest($validated, $hospital);
            return $this->successResponse($emergency, 'تم إنشاء طلب الطوارئ وجاري البحث عن المتبرعين الأقرب إليكم!', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * عرض تفاصيل طلب محدد
     */
    public function show($id)
    {
        $request = $this->emergencyRepo->findById($id);
        if (!$request) return $this->notFoundResponse();
        
        return $this->successResponse($request);
    }
}