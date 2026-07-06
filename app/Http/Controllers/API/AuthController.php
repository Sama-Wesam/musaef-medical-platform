<?php

namespace App\Http\Controllers\API;

use App\Services\AuthService;
use App\Services\HealthScreeningService; // تم استدعاء خدمة الاستبيان الصحي
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $authService;
    protected $healthService; // إضافة المتغير للخدمة الجديدة

    // حقن كل من AuthService و HealthScreeningService
    public function __construct(AuthService $authService, HealthScreeningService $healthService)
    {
        $this->authService = $authService;
        $this->healthService = $healthService;
    }

    /**
     * تسجيل الدخول
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $data = $this->authService->login($request->email, $request->password);
            return $this->successResponse($data, 'تم تسجيل الدخول بنجاح');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->getMessage(), 401);
        }
    }

    /**
     * تسجيل متبرع جديد وتقييم أهليته
     */
    public function registerDonor(Request $request)
    {
        // 1. التحقق من صحة البيانات (الأساسية + أسئلة الاستبيان الصحي)
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|string|email|unique:users',
            'password'         => 'required|string|min:8',
            'blood_type_id'    => 'required|exists:blood_types,id',
            'birth_date'       => 'required|date',
            'gender'           => 'required|in:male,female',
            // حقول الاستبيان الصحي
            'has_symptoms'     => 'nullable|boolean',
            'had_surgery'      => 'nullable|boolean',
            'takes_medication' => 'nullable|boolean',
            'is_pregnant'      => 'nullable|boolean',
        ]);

        try {
            // 2. إنشاء المستخدم والمتبرع عبر AuthService
            $result = $this->authService->registerDonor($validated);
            
            $user = $result['user'];
            $donor = $result['donor'];
            
            // 3. تحليل إجابات الاستبيان الصحي باستخدام HealthScreeningService
            $healthAnswers = $request->only([
                'has_symptoms', 
                'had_surgery', 
                'takes_medication', 
                'is_pregnant'
            ]);
            
            // سيتم تحديث حالة الأهلية (is_eligible) للمتبرع بداخل هذه الدالة
            $eligibilityResult = $this->healthService->evaluateHealthScreening($donor, $healthAnswers);

            // 4. إنشاء توكن الدخول
            $token = $user->createToken('musaef_auth_token')->plainTextToken;

            // 5. إرجاع الاستجابة متضمنة شارة الأهلية
            return $this->successResponse([
                'user'        => $user,
                'donor'       => $donor,
                'eligibility' => $eligibilityResult, // نرسلها للـ Frontend لإظهار الشارة
                'token'       => $token
            ], 'تم تسجيل حساب المتبرع وتقييم حالته بنجاح', 201);

        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage(), 500);
        }
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null, 'تم تسجيل الخروج بنجاح');
    }
}