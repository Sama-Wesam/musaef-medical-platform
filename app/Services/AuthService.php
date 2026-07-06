<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Enums\UserRole;
use App\Events\UserRegistered;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerDonor(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['role'] = UserRole::DONOR->value;
        
        $user = $this->userRepository->createUser($data);
        
        // إنشاء ملف المتبرع المرتبط
        $user->donor()->create([
            'blood_type_id' => $data['blood_type_id'] ?? null,
            'birth_date' => $data['birth_date'],
            'gender' => $data['gender'],
        ]);

        event(new UserRegistered($user));

        return $user;
    }

    public function registerHospital(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['role'] = UserRole::HOSPITAL->value;
        
        $user = $this->userRepository->createUser($data);
        
        // إنشاء ملف المستشفى المرتبط
        $user->hospital()->create([
            'license_number' => $data['license_number'],
            'address' => $data['address'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'is_verified' => false, // يحتاج تفعيل من الإدارة
        ]);

        event(new UserRegistered($user));

        return $user;
    }

    public function login(string $email, string $password)
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['البيانات المدخلة غير صحيحة.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['هذا الحساب موقوف، يرجى مراجعة الإدارة.'],
            ]);
        }

        $token = $user->createToken('musaef_auth_token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }
}