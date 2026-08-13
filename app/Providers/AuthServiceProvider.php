<?php

namespace App\Providers;

use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Hospital;
use App\Models\User;
use App\Policies\AdminPolicy;
use App\Policies\DonationPolicy;
use App\Policies\EmergencyPolicy;
use App\Policies\HospitalPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * خريطة ربط الموديلات بالسياسات الخاصة بها (Model to Policy Mappings)
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Hospital::class     => HospitalPolicy::class,
        BloodRequest::class => EmergencyPolicy::class,
        Donation::class     => DonationPolicy::class,
        User::class         => AdminPolicy::class, // ربط صريح لموديل المستخدمين بسياسة الأدمن
    ];

    /**
     * تسجيل خدمات المصادقة والتفويض
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
