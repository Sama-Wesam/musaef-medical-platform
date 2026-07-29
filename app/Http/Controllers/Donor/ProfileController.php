<?php

namespace App\Http\Controllers\Donor;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\HealthInfo;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    public function show(Request $request)
    {
        $donor = $request->user()->load(['user', 'healthInfo', 'bloodType']);

        if (!$donor) {
            return $this->notFoundResponse('بيانات المتبرع غير موجودة');
        }

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

        $userData = [];
        if (isset($validated['name'])) {
            $userData['name'] = $validated['name'];
        }
        if (isset($validated['email'])) {
            $userData['email'] = $validated['email'];
        }

        if (!empty($userData)) {
            $user->update($userData);
        }

        $donorData = [];
        if (isset($validated['phone'])) {
            $donorData['phone'] = $validated['phone'];
        }
        if (isset($validated['blood_type_id'])) {
            $donorData['blood_type_id'] = $validated['blood_type_id'];
        }

        // معالجة رفع الصورة الشخصية
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

    public function updateHealthQuestionnaire(Request $request)
    {
        $answers = $request->input('answers', []);
        $affirmativeCount = 0;
        foreach ($answers as $question) {
            if (isset($question['answer']) && $question['answer'] === true) {
                $affirmativeCount++;
            }
        }

        $isEligible = $affirmativeCount < 3;

        $result = $isEligible ? [
            'is_eligible' => true,
            'title' => 'حالتك الصحية مؤهلة للتبرع',
            'message' => 'بناءً على إجاباتك، يمكنك التبرع بالدم بأمان.'
        ] : [
            'is_eligible' => false,
            'title' => 'صحتك تهمنا',
            'message' => 'بناءً على إجاباتك الحالية، يفضل أخذ قسط من الراحة أو مراجعة الطبيب قبل التبرع حرصاً على سلامتك.'
        ];

        return $this->successResponse($result, 'تم تقييم الحالة الصحية بنجاح');
    }
}
