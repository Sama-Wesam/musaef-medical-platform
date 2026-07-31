<?php

namespace App\Http\Controllers\Donor;

use App\Services\HealthScreeningService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    protected $healthScreeningService;

    public function __construct(HealthScreeningService $healthScreeningService)
    {
        $this->healthScreeningService = $healthScreeningService;
    }

    public function show(Request $request)
    {
        // 1. الحصول على نموذج المتبرع المرتبط بالرمز الحالي
        $donor = $request->user()->donor;

        if (!$donor) {
            return $this->notFoundResponse('بيانات المتبرع غير موجودة');
        }

        // 2. تحميل العلاقات الخاصة بالمتبرع بالشكل الصحيح
        $donor->load(['user', 'healthInfo', 'bloodType']);

        return $this->successResponse($donor, 'تم جلب الملف الشخصي');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|string|max:20',
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = $request->user();
        $donor = $user->donor;

        if (!$donor) {
            return $this->notFoundResponse('بيانات المتبرع غير موجودة');
        }

        if (isset($validated['name']) || isset($validated['email'])) {
            $user->update(array_filter([
                'name' => $validated['name'] ?? null,
                'email' => $validated['email'] ?? null,
            ]));
        }

        $donorData = array_filter([
            'phone' => $validated['phone'] ?? null,
            'blood_type_id' => $validated['blood_type_id'] ?? null,
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
            $donorData['avatar'] = $path;
        }

        if (!empty($donorData)) {
            $donor->update($donorData);
        }

        $donor->load(['user', 'healthInfo', 'bloodType']);

        return $this->successResponse($donor, 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * تحديث وحفظ استبيان الأهلية الصحية الاعتماد على HealthScreeningService
     */
    public function updateHealthQuestionnaire(Request $request)
    {
        $donor = $request->user()->donor ?? null;
        if (!$donor) {
            return $this->notFoundResponse('بيانات المتبرع غير موجودة');
        }

        $answers = $request->input('answers', []);

        // التقييم الطبي وحفظ سجل الأهلية عبر خدمة الفحص الطبي الموحدة
        $evaluation = $this->healthScreeningService->evaluateHealthScreening($donor, $answers);

        return $this->successResponse($evaluation, 'تم تقييم وحفظ الحالة الصحية بنجاح');
    }
}
