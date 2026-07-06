<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    protected $table = 'donors';

    protected $fillable = [
        'user_id',
        'blood_type_id',
        'birth_date',
        'gender',
        'latitude',
        'longitude',
        'address',
        'is_available',
        'last_donation_date',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'birth_date' => 'date',
        'last_donation_date' => 'date',
    ];

    // علاقة المتبرع بحساب المستخدم الأساسي
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة المتبرع بفصيلة الدم
    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    // علاقة المتبرع ببياناته الصحية (علاقة 1 إلى 1)
    public function healthInfo()
    {
        return $this->hasOne(HealthInfo::class);
    }

    // سجل تبرعات المتبرع
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    // استجابات المتبرع لطلبات الطوارئ
    public function responses()
    {
        return $this->hasMany(DonorResponse::class);
    }
}