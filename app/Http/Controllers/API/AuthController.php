<?php

namespace App\Http\Controllers\API;

use App\Services\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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
     * تسجيل متبرع جديد وتقييم أهليته الصحية
     */
    public function registerDonor(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|string|email|unique:users',
            'password'         => 'required|string|min:8',
            'blood_type_id'    => 'required|exists:blood_types,id',
            'birth_date'       => 'required|date',
            'gender'           => 'required|in:male,female',
            'has_symptoms'     => 'nullable|boolean',
            'had_surgery'      => 'nullable|boolean',
            'takes_medication' => 'nullable|boolean',
            'is_pregnant'      => 'nullable|boolean',
        ]);

        try {
            $result = $this->authService->registerDonor($validated);

            $user = $result['user'];
            $donor = $result['donor'];

            $healthAnswers = $request->only([
                'has_symptoms',
                'had_surgery',
                'takes_medication',
                'is_pregnant'
            ]);

            // استدعاء دالة التقييم الصحي المدمجة داخل AuthService بنجاح
            $eligibilityResult = $this->authService->evaluateHealthScreening($donor, $healthAnswers);

            $token = $user->createToken('musaef_auth_token')->plainTextToken;

            return $this->successResponse([
                'user'        => $user,
                'donor'       => $donor,
                'eligibility' => $eligibilityResult,
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
