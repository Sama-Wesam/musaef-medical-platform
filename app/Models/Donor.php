<?php

namespace App\Models;

use App\Traits\LocationTrait;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use LocationTrait;

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
        'is_eligible',
        'eligibility_status',
        'deferral_date',
        'last_donation_date',
    ];

    protected $casts = [
        'is_available'       => 'boolean',
        'is_eligible'        => 'boolean',
        'birth_date'         => 'date',
        'deferral_date'      => 'date',
        'last_donation_date' => 'date',
    ];

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
}
