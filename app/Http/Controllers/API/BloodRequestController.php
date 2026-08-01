<?php

namespace App\Http\Controllers\API;

use App\Models\BloodRequest;
use App\Models\BloodType;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BloodRequestController extends Controller
{
    use ApiResponseTrait;

    /**
     * عرض جميع طلبات التبرع
     */
    public function index(Request $request)
    {
        $requests = BloodRequest::with(['hospital', 'bloodType'])
            ->latest()
            ->get();

        return $this->successResponse($requests, 'تم جلب طلبات التبرع بنجاح');
    }

    /**
     * إنشاء طلب طارئ جديد للدم
     */
    public function store(Request $request)
    {
        // 1. التعرف التلقائي وتحويل اسم الفصيلة النصي إلى blood_type_id إن وجد
        if (!$request->has('blood_type_id') && $request->has('blood_type')) {
            $bloodTypeName = trim($request->input('blood_type'));
            $bloodTypeRecord = BloodType::where('name', $bloodTypeName)
                ->orWhere('code', $bloodTypeName)
                ->first();

            if ($bloodTypeRecord) {
                $request->merge(['blood_type_id' => $bloodTypeRecord->id]);
            }
        }

        // 2. التحقق من صحة المدخلات
        $validated = $request->validate([
            'blood_type_id' => 'required|exists:blood_types,id',
            'units_required' => 'required|integer|min:1',
            'urgency_level' => 'nullable|string',
            'emergency_level' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        $hospitalId = $user->hospital ? $user->hospital->id : $user->id;

        // 3. إنشاء النداء الطارئ
        $bloodRequest = BloodRequest::create([
            'hospital_id'    => $hospitalId,
            'blood_type_id'  => $validated['blood_type_id'],
            'units_required' => $validated['units_required'],
            'urgency_level'  => $validated['urgency_level'] ?? $validated['emergency_level'] ?? 'critical',
            'status'         => 'active',
            'notes'          => $validated['notes'] ?? null,
            'request_code'   => 'ER-' . date('Y') . '-' . rand(1000, 9999),
        ]);

        $bloodRequest->load(['hospital', 'bloodType']);

        return $this->successResponse($bloodRequest, 'تم إطلاق النداء الطارئ وتنبيه المتبرعين المطابقين فوراً!', 201);
    }
}
