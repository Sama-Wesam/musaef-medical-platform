<?php

namespace App\Services;

use App\Models\User;
use App\Models\Donor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DonationService
{
    /**
     * تسجيل متبرع جديد مع ربطه بالمستخدم بنفس الـ ID
     */
    public function registerDonor(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. إنشاء المستخدم
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => 'donor',
            ]);

            // 2. إنشاء سجل المتبرع بنفس الـ ID الناتج من المستخدم
            $donor = Donor::create([
                'id'            => $user->id, // هنا الربط المباشر (Shared Primary Key)
                'blood_type_id' => $data['blood_type_id'],
                'birth_date'    => $data['birth_date'],
                'gender'        => $data['gender'],
            ]);

            return ['user' => $user, 'donor' => $donor];
        });
    }

    /**
     * تسجيل الدخول
     */
    public function login($email, $password)
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['البيانات المدخلة غير صحيحة.'],
            ]);
        }

        $token = $user->createToken('musaef_auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
