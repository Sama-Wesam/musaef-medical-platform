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
     * عرض المخزون الحالي والإحصائيات الحية
     */
    public function index(Request $request)
    {
        $hospital = $request->user()->hospital;
        if (!$hospital) {
            return $this->notFoundResponse('حساب المستخدم الحالي غير مرتبط بجهة طبية');
        }

        $inventoryData = $this->hospitalService->getInventoryData($hospital->id);

        return $this->successResponse($inventoryData, 'تم جلب بيانات مخزون بنك الدم والإحصائيات بنجاح');
    }

    /**
     * ⚡ دالة Polling سريعة جداً للمخزون
     */
    public function liveInventoryPoll(Request $request)
    {
        $hospital = $request->user()->hospital;
        if (!$hospital) {
            return $this->notFoundResponse('حساب المستخدم غير مرتبط بجهة طبية');
        }

        $inventoryData = $this->hospitalService->getInventoryData($hospital->id);

        return $this->successResponse([
            'inventory' => $inventoryData,
            'timestamp' => now()->toDateTimeString()
        ], 'تم إرجاع أحدث بيانات المخزون المباشر');
    }

    /**
     * التحديث اليدوي للمخزون
     */
    public function update(Request $request)
    {
        $hospital = $request->user()->hospital;
        if (!$hospital) {
            return $this->notFoundResponse('حساب المستخدم الحالي غير مرتبط بجهة طبية');
        }

        $validated = $request->validate([
            'blood_type_id' => 'required|exists:blood_types,id',
            'units'         => 'required|integer|min:1',
            'operation'     => 'required|in:add,sub',
            'notes'         => 'nullable|string|max:255'
        ]);

        $success = $this->hospitalService->manualInventoryUpdate(
            $hospital->id,
            $validated['blood_type_id'],
            $validated['units'],
            $validated['operation'],
            $validated['notes'] ?? null
        );

        if ($success) {
            $actionText = $validated['operation'] === 'add' ? 'إضافة' : 'خصم';
            return $this->successResponse(null, "تم {$actionText} الوحدات بنجاح");
        }

        return response()->json([
            'success' => false,
            'message' => 'لا توجد كمية كافية للخصم من المخزون، تأكد من وجود كمية كافية للخصم'
        ], 400);
    }
}
