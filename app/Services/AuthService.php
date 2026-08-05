<?php

namespace App\Services;

use App\Models\User;
use App\Models\Donor;
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
                'password' => $data['password'], 
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
}
