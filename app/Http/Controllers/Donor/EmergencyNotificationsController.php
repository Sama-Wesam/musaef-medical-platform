<?php

namespace App\Http\Controllers\Donor;

use App\Models\DonorResponse;
use App\Repositories\EmergencyRepository; // استدعاء مستودع الطوارئ
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmergencyNotificationsController extends Controller
{
    use ApiResponseTrait;

    protected $emergencyRepo;

    // حقن مستودع الطوارئ لجلب النداءات النشطة المتوافقة مع النظام
    public function __construct(EmergencyRepository $emergencyRepo)
    {
        $this->emergencyRepo = $emergencyRepo;
    }

    /**
     * جلب قائمة بنداءات الطوارئ النشطة والمفتوحة ليتبرع من خلالها المستخدم
     */
    public function index(Request $request)
    {
        // جلب الحالات النشطة مباشرة بدلاً من جلب الاستجابات المسبقة فقط
        $emergencies = $this->emergencyRepo->getActiveEmergencies();

        return $this->successResponse($emergencies, 'تم جلب نداءات الطوارئ المتاحة بنجاح');
    }

    /**
     * تحديث حالة الاستجابة مع تأمين صلاحية وحماية السجل
     */
    public function update(Request $request, $id)
    {
        $donorId = $request->user()->donor->id;

        // تأمين الفحص الشرطي لضمان أن المتبرع يعدل فقط على استجابته الخاصة منعاً للثغرات الأمنية
        $donorResponse = DonorResponse::where('id', $id)
            ->where('donor_id', $donorId)
            ->first();

        if (!$donorResponse) {
            return $this->notFoundResponse('سجل الاستجابة غير موجود أو غير مصرح لك بتعديله');
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,declined',
            'eta_minutes' => 'nullable|integer'
        ]);

        $donorResponse->update($validated);

        return $this->successResponse($donorResponse, 'تم تسجيل استجابتك، شكراً لتعاونك!');
    }
}
