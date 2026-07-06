<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\EmergencyRepository;
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

    /**
     * جلب جميع طلبات الطوارئ النشطة
     */
    public function index()
    {
        $requests = $this->emergencyRepo->getActiveEmergencies();
        return $this->successResponse($requests, 'تم جلب طلبات الطوارئ');
    }

    /**
     * عرض تفاصيل طلب طوارئ
     */
    public function show($id)
    {
        $request = $this->emergencyRepo->findById($id);
        if (!$request) return $this->notFoundResponse();

        return $this->successResponse($request);
    }

    /**
     * الإلغاء الإداري لطلب طوارئ (في حال كان الطلب وهمياً أو بالخطأ)
     */
    public function cancelRequest($id)
    {
        $updated = $this->emergencyRepo->updateStatus($id, 'cancelled');
        
        if ($updated) {
            return $this->successResponse(null, 'تم إلغاء الطلب إدارياً');
        }

        return $this->errorResponse('فشل في إلغاء الطلب');
    }
}