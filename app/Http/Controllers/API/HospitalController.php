<?php

namespace App\Http\Controllers\API;

use App\Services\HospitalService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HospitalController extends Controller
{
    use ApiResponseTrait;

    protected $hospitalService;

    public function __construct(HospitalService $hospitalService)
    {
        $this->hospitalService = $hospitalService;
    }

    public function inventory(Request $request)
    {
        $hospital = $request->user()->hospital ?? null;

        if (!$hospital) {
            return $this->unauthorizedResponse('حساب المستشفى غير متاح أو غير معرّف');
        }

        $inventory = $this->hospitalService->getInventory($hospital->id);

        return $this->successResponse($inventory, 'تم جلب مخزون بنك الدم بنجاح');
    }

    /**
     * ⚡ دالة Polling سريعة لتحديث مخزون المستشفى في الوقت الحقيقي
     */
    public function liveInventoryStats(Request $request)
    {
        $hospital = $request->user()->hospital ?? null;

        if (!$hospital) {
            return $this->unauthorizedResponse('غير مصرح للوصول لبيانات المخزون');
        }

        $inventory = $this->hospitalService->getInventory($hospital->id);

        return $this->successResponse([
            'inventory' => $inventory,
            'updated_at' => now()->toDateTimeString()
        ], 'تم تحديث المخزون الحقيقي بنجاح');
    }
}
