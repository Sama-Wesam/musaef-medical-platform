<?php

namespace App\Models;

use App\Traits\LocationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory, LocationTrait;

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
        'eligibility_status',
        'deferral_date',
        'last_donation_date',
    ];

    protected $casts = [
        'is_available'       => 'boolean',
        'birth_date'         => 'date',
        'deferral_date'      => 'date',
        'last_donation_date' => 'date',
    ];

    // -----------------------------------------------------------------
    // Accessors (المُعاملات المحسوبة)
    // -----------------------------------------------------------------

    /**
     * حساب عمر المتبرع تلقائياً من تاريخ الميلاد
     */
    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    // -----------------------------------------------------------------
    // العلاقات (Relationships)
    // -----------------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    public function healthInfo()
    {
        return $this->hasOne(HealthInfo::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function responses()
    {
        return $this->hasMany(DonorResponse::class);
    }

    public function matchingResults()
    {
        return $this->hasMany(MatchingResult::class);
    }
}
