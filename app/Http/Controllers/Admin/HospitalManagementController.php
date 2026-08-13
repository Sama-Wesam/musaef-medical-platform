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

    public function index()
    {
        $hospitals = Hospital::with('user')->get();
        return $this->successResponse($hospitals, 'تم جلب المستشفيات');
    }

    /**
     * ⚡ دالة Polling لمتابعة حالة توثيق واعتماد وحظر الجهات الطبية لحظياً
     */
    public function liveHospitalsStatus()
    {
        $stats = [
            'total'      => Hospital::count(),
            'verified'   => Hospital::where('is_verified', true)->count(),
            'unverified' => Hospital::where('is_verified', false)->count(),
            'suspended'  => Hospital::where('status', 'suspended_ai')->count(),
            'timestamp'  => now()->toDateTimeString()
        ];

        return $this->successResponse($stats, 'تم جلب حالة المستشفيات اللحظية بنجاح');
    }

    public function show($id)
    {
        $hospital = Hospital::with(['user', 'bloodInventories'])->find($id);

        if (!$hospital) {
            return $this->notFoundResponse('المستشفى غير موجود');
        }

        return $this->successResponse($hospital);
    }

    public function verifyHospital($id)
    {
        $hospital = Hospital::find($id);

        if (!$hospital) {
            return $this->notFoundResponse();
        }

        $hospital->update(['is_verified' => true]);

        return $this->successResponse(null, 'تم توثيق المستشفى بنجاح');
    }

    public function destroy($id)
    {
        $hospital = Hospital::find($id);
        if ($hospital) {
            $hospital->user()->delete();
            $hospital->delete();
            return $this->successResponse(null, 'تم حذف المستشفى بنجاح');
        }
        return $this->errorResponse('المستشفى غير موجود');
    }
}
