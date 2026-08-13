<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Services\HealthScreeningService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\HealthInfo;
use App\Models\Donor;
use Throwable;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    protected $healthScreeningService;

    public function __construct(HealthScreeningService $healthScreeningService)
    {
        $this->healthScreeningService = $healthScreeningService;
    }

    /**
     * جلب الملف الشخصي الكامل للمتبرع مع العلاقات وإلحاق رابط الصورة المباشر
     */
    public function show(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح بالوصول، يرجى إعادة تسجيل الدخول'
                ], 401);
            }

            $donor = $user->donor ?? Donor::firstOrCreate(
                ['user_id' => $user->id],
                ['is_available' => true]
            );

            $donor->load(['user', 'healthInfo', 'bloodType']);

            $avatarPath = null;
            if ($user->image) {
                $avatarPath = filter_var($user->image, FILTER_VALIDATE_URL)
                    ? $user->image
                    : asset('storage/' . ltrim($user->image, '/'));
            }

            $responseData = $donor->toArray();
            $responseData['avatar_url'] = $avatarPath;
            if (isset($responseData['user'])) {
                $responseData['user']['avatar_url'] = $avatarPath;
            }

            return response()->json([
                'success' => true,
                'data' => $responseData,
                'message' => 'تم جلب الملف الشخصي بنجاح'
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الملف الشخصي: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث البيانات الشخصية والصحية والصورة بأسلوب آمن
     */
    public function update(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح بالوصول'
                ], 401);
            }

            $donor = $user->donor ?? Donor::firstOrCreate(['user_id' => $user->id]);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'blood_type_id' => 'nullable|exists:blood_types,id',
                'weight' => 'nullable|numeric|min:30|max:200',
                'last_donation_date' => 'nullable|date',
                'is_available' => 'nullable|boolean',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            // 1. تحديث اسم وبريد المستخدم
            if (isset($validated['name']) || isset($validated['email'])) {
                $user->update(array_filter([
                    'name' => $validated['name'] ?? null,
                    'email' => $validated['email'] ?? null,
                ]));
            }

            // 2. حفظ الصورة الجديدة وترفيعها بجدول users عبر عمود image
            $imageFile = $request->file('avatar') ?? $request->file('image');
            if ($imageFile) {
                if ($user->image && Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }
                $path = $imageFile->store('avatars', 'public');
                $user->image = $path;
                $user->save();
            }

            // 3. تحديث بيانات المتبرع
            $donorData = [];
            if (array_key_exists('phone', $validated)) $donorData['phone'] = $validated['phone'];
            if (array_key_exists('blood_type_id', $validated)) $donorData['blood_type_id'] = $validated['blood_type_id'];
            if (array_key_exists('is_available', $validated)) $donorData['is_available'] = $validated['is_available'];
            if (array_key_exists('last_donation_date', $validated)) $donorData['last_donation_date'] = $validated['last_donation_date'];

            if (!empty($donorData)) {
                $donor->update($donorData);
            }

            // 4. تحديث البيانات الصحية
            if (isset($validated['weight'])) {
                HealthInfo::updateOrCreate(
                    ['donor_id' => $donor->id],
                    ['weight' => $validated['weight']]
                );
            }

            $user->refresh();
            $donor->refresh();
            $donor->load(['user', 'healthInfo', 'bloodType']);

            $fullAvatarUrl = null;
            if ($user->image) {
                $fullAvatarUrl = filter_var($user->image, FILTER_VALIDATE_URL)
                    ? $user->image
                    : asset('storage/' . ltrim($user->image, '/'));
            }

            $responseData = $donor->toArray();
            $responseData['avatar_url'] = $fullAvatarUrl;
            if (isset($responseData['user'])) {
                $responseData['user']['avatar_url'] = $fullAvatarUrl;
            }

            return response()->json([
                'success' => true,
                'data' => $responseData,
                'message' => 'تم تحديث الملف الشخصي بنجاح'
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث وحفظ استبيان الأهلية الصحية
     */
    public function updateHealthQuestionnaire(Request $request)
    {
        try {
            $donor = $request->user()->donor ?? null;

            if (!$donor) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات المتبرع غير موجودة'
                ], 404);
            }

            $answers = $request->input('answers', $request->all());
            $evaluation = $this->healthScreeningService->evaluateHealthScreening($donor, $answers);

            return response()->json([
                'success' => true,
                'data' => $evaluation,
                'message' => 'تم تقييم وحفظ الحالة الصحية بنجاح'
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الاستبيان الصحي: ' . $e->getMessage()
            ], 500);
        }
    }
}
