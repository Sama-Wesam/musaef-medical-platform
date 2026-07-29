<?php

namespace App\Services;

use App\Models\User;
use App\Models\Donor;
use App\Models\HealthInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * تسجيل الدخول الموحد بأمان
     */
    public function login($email, $password)
    {
        $user = User::where('email', $email)->first();

        // التحقق من وجود المستخدم وصحة كلمة المرور
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['البريد الإلكتروني أو كلمة المرور غير صحيحة.'],
            ]);
        }

        // إنشاء التوكن المعتمد للفرونت إند
        $token = $user->createToken('musaef_auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * تسجيل متبرع جديد مع ربطه بالمستخدم
     */
    public function registerDonor(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'], // تم إزالة Hash::make لأن موديل User يتضمن 'password' => 'hashed'
                'role'     => 'donor',
            ]);

            $donor = Donor::create([
                'user_id'       => $user->id,
                'blood_type_id' => $data['blood_type_id'],
                'birth_date'    => $data['birth_date'],
                'gender'        => $data['gender'],
                'is_available'  => true,
            ]);

            return ['user' => $user, 'donor' => $donor];
        });
    }

    /**
     * تقييم استبيان حالة المتبرع الصحية
     */
    public function evaluateHealthScreening(Donor $donor, array $answers)
    {
        $isEligible = true;
        $deferralDate = null;
        $message = 'أنت مؤهل فوراً للتبرع!';
        $status = 'eligible';

        if (isset($answers['has_symptoms']) && $answers['has_symptoms'] == true) {
            $isEligible = false;
            $status = 'suspended';
            $message = 'سلامتك تهمنا! نرجو منك التبرع حين تتحسن حالتك الصحية.';
        } elseif (
            (isset($answers['had_surgery']) && $answers['had_surgery'] == true) ||
            (isset($answers['is_pregnant']) && $answers['is_pregnant'] == true)
        ) {
            $isEligible = false;
            $status = 'deferred';
            $deferralDate = Carbon::now()->addMonths(6);
            $message = 'عذراً، أنت غير مؤهل حالياً للتبرع.';
        }

        $donor->update([
            'is_eligible' => $isEligible,
            'eligibility_status' => $status,
            'deferral_date' => $deferralDate,
        ]);

        HealthInfo::updateOrCreate(
            ['donor_id' => $donor->id],
            [
                'has_symptoms' => $answers['has_symptoms'] ?? false,
                'had_surgery' => $answers['had_surgery'] ?? false,
                'takes_medication' => $answers['takes_medication'] ?? false,
                'is_pregnant' => $answers['is_pregnant'] ?? false,
            ]
        );

        return [
            'is_eligible' => $isEligible,
            'status' => $status,
            'message' => $message,
            'deferral_date' => $deferralDate ? $deferralDate->format('Y-m-d') : null,
        ];
    }
}
