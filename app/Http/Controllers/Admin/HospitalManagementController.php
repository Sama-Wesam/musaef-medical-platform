<?php

namespace App\Http\Controllers\Admin;

use App\Services\HospitalService;
use App\Traits\ApiResponseTrait;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HospitalManagementController extends Controller
{
    use ApiResponseTrait;

    protected $hospitalService;

    public function __construct(HospitalService $hospitalService)
    {
        $this->hospitalService = $hospitalService;
    }

    /**
     * جلب قائمة بجميع المستشفيات
     */
    public function index()
    {
        $hospitals = Hospital::with('user')->get();
        return $this->successResponse($hospitals, 'تم جلب المستشفيات');
    }

    /**
     * عرض بيانات مستشفى محدد
     */
    public function show($id)
    {
        $hospital = Hospital::with(['user', 'bloodInventories'])->find($id);
        
        if (!$hospital) {
            return $this->notFoundResponse('المستشفى غير موجود');
        }

        return $this->successResponse($hospital);
    }

    /**
     * توثيق واعتماد مستشفى جديد ليتمكن من طلب الدم
     */
    public function verifyHospital($id)
    {
        $hospital = Hospital::find($id);
        
        if (!$hospital) {
            return $this->notFoundResponse();
        }

        $hospital->update(['is_verified' => true]);

        return $this->successResponse(null, 'تم توثيق المستشفى بنجاح');
    }

    /**
     * إيقاف أو حذف مستشفى
     */
    public function destroy($id)
    {
        $hospital = Hospital::find($id);
        if ($hospital) {
            $hospital->user()->delete(); // يحذف حساب المستخدم المرتبط به
            $hospital->delete();
            return $this->successResponse(null, 'تم حذف المستشفى بنجاح');
        }
        return $this->errorResponse('المستشفى غير موجود');
    }
}