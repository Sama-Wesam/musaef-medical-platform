<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * الحقول القابلة للتعبئة.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role', // admin, donor, hospital, guest
        'is_active',
        'password',
    ];

    /**
     * الحقول المخفية.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * تحويل أنواع البيانات.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // -----------------------------------------------------------------
    // Helper Methods (دوال مساعدة لفحص الصلاحيات والأدوار)
    // -----------------------------------------------------------------

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHospital(): bool
    {
        return $this->role === 'hospital';
    }

    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    // -----------------------------------------------------------------
    // العلاقات (Relationships)
    // -----------------------------------------------------------------

    // علاقة المستخدم كـ (متبرع)
    public function donor()
    {
        return $this->hasOne(Donor::class);
    }

    // علاقة المستخدم كـ (مستشفى)
    public function hospital()
    {
        return $this->hasOne(Hospital::class);
    }

    // الإشعارات الخاصة بالمستخدم
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
