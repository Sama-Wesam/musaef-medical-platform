<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Services\HealthScreeningService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Hospital;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $authService;
    protected $healthScreeningService;

    public function __construct(AuthService $authService, HealthScreeningService $healthScreeningService)
    {
        $this->authService = $authService;
        $this->healthScreeningService = $healthScreeningService;
    }

    /**
     * تسجيل الدخول الموحد
     */
    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService->login($request->email, $request->password);
            return $this->successResponse($data, 'تم تسجيل الدخول بنجاح');
        } catch (ValidationException $e) {
            return $this->errorResponse('بيانات الاعتماد غير صحيحة.', 401, $e->errors());
        } catch (\Throwable $e) {
            return $this->errorResponse('حدث خطأ في السيرفر: ' . $e->getMessage(), 500);
        }
    }

    /**
     * إرسال رابط استعادة كلمة المرور
     */
    public function forgotPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|exists:users,email',
            ], [
                'email.required' => 'البريد الإلكتروني مطلوب.',
                'email.email'    => 'صيغة البريد الإلكتروني غير صحيحة.',
                'email.exists'   => 'البريد الإلكتروني غير مسجل لدينا.',
            ]);

            return $this->successResponse(null, 'تم إرسال رابط إعادة تعيين كلمة المرور بنجاح!');

        } catch (ValidationException $e) {
            return $this->errorResponse('بيانات البريد الإلكتروني غير صحيحة.', 422, $e->errors());
        } catch (\Throwable $e) {
            return $this->errorResponse('حدث خطأ أثناء إرسال رابط استعادة كلمة المرور: ' . $e->getMessage(), 500);
        }
    }

    /**
     * إعادة تعيين كلمة المرور
     */
    public function resetPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'token'                 => 'required|string',
                'email'                 => 'required|email|exists:users,email',
                'password'              => 'required|string|min:8|confirmed',
            ], [
                'token.required'        => 'رمز التفعيل مطلوب.',
                'email.required'        => 'البريد الإلكتروني مطلوب.',
                'email.exists'          => 'البريد الإلكتروني غير مسجل لدينا.',
                'password.required'     => 'كلمة المرور الجديدة مطلوبة.',
                'password.min'          => 'كلمة المرور يجب أن لا تقل عن 8 أحرف.',
                'password.confirmed'    => 'تأكيد كلمة المرور غير متطابق.',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return $this->errorResponse('المستخدم غير موجود.', 404);
            }

            // تحديث كلمة المرور
            $user->password = Hash::make($validated['password']);
            $user->save();

            return $this->successResponse(null, 'تم إعادة تعيين كلمة المرور بنجاح!');

        } catch (ValidationException $e) {
            return $this->errorResponse('بيانات إعادة التعيين غير صحيحة.', 422, $e->errors());
        } catch (\Throwable $e) {
            return $this->errorResponse('حدث خطأ أثناء إعادة تعيين كلمة المرور: ' . $e->getMessage(), 500);
        }
    }

    /**
     * تسجيل متبرع جديد وتقييم أهليته الصحية
     */
    public function registerDonor(RegisterRequest $request)
    {
        try {
            $result = $this->authService->registerDonor($request->validated());

            $user = $result['user'];
            $donor = $result['donor'];

            $healthAnswers = $request->only([
                'has_symptoms',
                'had_surgery',
                'takes_medication',
                'is_pregnant'
            ]);

            $eligibilityResult = $this->healthScreeningService->evaluateHealthScreening($donor, $healthAnswers);
            $token = $user->createToken('musaef_auth_token')->plainTextToken;

            return $this->successResponse([
                'user'        => $user,
                'donor'       => $donor,
                'eligibility' => $eligibilityResult,
                'token'       => $token
            ], 'تم تسجيل حساب المتبرع وتقييم حالته بنجاح', 201);

        } catch (\Throwable $e) {
            return $this->errorResponse('حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage(), 500);
        }
    }

    /**
     * تسجيل مستشفى / بنك دم جديد
     */
    public function registerHospital(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'                  => 'required|string|max:255',
                'email'                 => 'required|string|email|max:255|unique:users,email',
                'phone'                 => 'required|string|max:20',
                'password'              => 'required|string|min:8|confirmed',
                'license_number'        => 'required|string|max:100',
                'address'               => 'nullable|string|max:255',  // تم جعله اختياريًا
                'latitude'              => 'nullable|numeric',          // تم جعله اختياريًا
                'longitude'             => 'nullable|numeric',         // تم جعله اختياريًا
                'facility_type'         => 'nullable|string|max:50',
                'facility_name'         => 'nullable|string|max:255',
            ]);

            // 1. إنشاء حساب المستخدم في جدول users
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'phone'    => $validated['phone'],
                'password' => bcrypt($validated['password']),
                'role'     => 'hospital',
            ]);

            // 2. إنشاء سجل المستشفى مع استخدام القيم الافتراضية في حال عدم التمرير
            $hospital = Hospital::create([
                'user_id'        => $user->id,
                'facility_name'  => $validated['facility_name'] ?? $validated['name'],
                'facility_type'  => $validated['facility_type'] ?? 'مستشفى',
                'license_number' => $validated['license_number'],
                'address'        => $validated['address'] ?? 'غزة - فلسطين', // قيمة افتراضية في حال التمرير كـ null
                'latitude'       => $validated['latitude'] ?? 31.5017,        // إحداثيات افتراضية لغزة
                'longitude'      => $validated['longitude'] ?? 34.4668,       // إحداثيات افتراضية لغزة
                'is_verified'    => true,
            ]);

            $token = $user->createToken('musaef_auth_token')->plainTextToken;

            return $this->successResponse([
                'user'     => $user,
                'hospital' => $hospital,
                'token'    => $token
            ], 'تم تسجيل حساب المستشفى بنجاح', 201);

        } catch (ValidationException $e) {
            return $this->errorResponse('خطأ في البيانات المرفقة', 422, $e->errors());
        } catch (\Throwable $e) {
            return $this->errorResponse('حدث خطأ أثناء إنشاء حساب المستشفى: ' . $e->getMessage(), 500);
        }
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }
        return $this->successResponse(null, 'تم تسجيل الخروج بنجاح');
    }
}
