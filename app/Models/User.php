<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'role',
        'is_active',
        'fcm_token',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'role'              => UserRole::class,
        ];
    }

    /**
     * توجيه إشعارات FCM للتوكن الخاص بجهاز المستخدم
     */
    public function routeNotificationForFcm(): ?string
    {
        return $this->fcm_token;
    }

    // -----------------------------------------------------------------
    // Helper Methods
    // -----------------------------------------------------------------

    public function isAdmin(): bool
    {
        $roleValue = is_object($this->role) && method_exists($this->role, 'value')
            ? $this->role->value
            : (string) $this->role;

        return strtolower($roleValue) === 'admin';
    }

    public function isHospital(): bool
    {
        $roleValue = is_object($this->role) && method_exists($this->role, 'value')
            ? $this->role->value
            : (string) $this->role;

        return strtolower($roleValue) === 'hospital';
    }

    public function isDonor(): bool
    {
        $roleValue = is_object($this->role) && method_exists($this->role, 'value')
            ? $this->role->value
            : (string) $this->role;

        return strtolower($roleValue) === 'donor';
    }

    // -----------------------------------------------------------------
    // Relationships
    // -----------------------------------------------------------------

    public function donor()
    {
        return $this->hasOne(Donor::class);
    }

    public function hospital()
    {
        return $this->hasOne(Hospital::class);
    }

    public function customNotifications()
    {
        return $this->hasMany(Notification::class);
    }
}
