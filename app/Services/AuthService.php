<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use App\Models\Donor;
use App\Models\HealthInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Enums\UserRole;
use App\Events\UserRegistered;
use Carbon\Carbon;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerDonor(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['password'] = Hash::make($data['password']);
            $data['role'] = UserRole::DONOR->value;

            $user = $this->userRepository->createUser($data);

            // إنشاء ملف المتبرع المرتبط مع تأمين الأهلية الافتراضية
            $donor = $user->donor()->create([
                'blood_type_id' => $data['blood_type_id'] ?? null,
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'is_eligible' => true,
                'eligibility_status' => 'eligible'
            ]);

            event(new UserRegistered($user));

            return ['user' => $user, 'donor' => $donor];
        });
    }

    public function registerHospital(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $data['password'] = Hash::make($data['password']);
            $data['role'] = UserRole::HOSPITAL->value;

            $user = $this->userRepository->createUser($data);

            $user->hospital()->create([
                'license_number' => $data['license_number'],
                'address' => $data['address'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'is_verified' => false,
            ]);

            event(new UserRegistered($user));

            return $user;
        });
    }

    public function evaluateHealthScreening(Donor $donor, array $answers)
    {
        $isEligible = true;
        $deferralDate = null;
        $message = 'أنت مؤهل فوراً للتبرع! لقد أضفنا شارة المؤهل للتبرع إلى لوحة تحكمك.';
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
            $message = 'عذراً، أنت غير مؤهل حالياً للتبرع. ننتظر انضمامك بعد 6 أشهر لضمان سلامتك.';
        } elseif (isset($answers['takes_medication']) && $answers['takes_medication'] == true) {
            $status = 'eligible_with_review';
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

    public function login(string $email, string $password)
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['البيانات المدخلة غير صحيحة.'],
            ]);
        }

        $token = $user->createToken('musaef_auth_token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }
}
