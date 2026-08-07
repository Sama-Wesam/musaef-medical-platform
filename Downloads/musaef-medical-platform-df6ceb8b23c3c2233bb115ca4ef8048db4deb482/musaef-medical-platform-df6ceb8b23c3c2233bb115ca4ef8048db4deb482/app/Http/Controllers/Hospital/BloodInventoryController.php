<?php

namespace App\Http\Controllers\Hospital;

use App\Services\HospitalService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BloodInventoryController extends Controller
{
    use ApiResponseTrait;

    protected $hospitalService;

    public function __construct(HospitalService $hospitalService)
    {
        $this->hospitalService = $hospitalService;
    }

    /**
     * عرض المخزون الحالي
     */
    public function index(Request $request)
    {
        $hospitalId = $request->user()->hospital->id;
        $inventory = $this->hospitalService->getInventory($hospitalId);
        
        return $this->successResponse($inventory, 'تم جلب مخزون الدم الخاص بالمستشفى');
    }

    /**
     * التحديث اليدوي للمخزون (إضافة أو خصم وحدات)
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'blood_type_id' => 'required|exists:blood_types,id',
            'units' => 'required|integer|min:1',
            'operation' => 'required|in:add,sub' // add للإضافة، sub للخصم
        ]);

        $hospitalId = $request->user()->hospital->id;
        
        $success = $this->hospitalService->manualInventoryUpdate(
            $hospitalId,
            $validated['blood_type_id'],
            $validated['units'],
            $validated['operation']
        );

        if ($success) {
            return $this->successResponse(null, 'تم تحديث المخزون بنجاح');
        }

        return $this->errorResponse('فشل في تحديث المخزون، تأكد من وجود كمية كافية للخصم');
    }
}