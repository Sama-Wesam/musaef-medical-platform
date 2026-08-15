<?php

namespace App\Models;

use App\Traits\LocationTrait;
use Illuminate\Database\Eloquent\Builder;
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
        'city', // ⚡ تم إضافة عمود المدينة هنا لحل مشكلة Mass Assignment أثناء الـ Seeding
        'latitude',
        'longitude',
        'address',
        'is_available',
        'eligibility_status',
        'deferral_date',
        'last_donation_date',
    ];

    protected $hidden = [
        'created_at', // تم إخفاؤها لتسريع استجابة الـ API وتقليل حجم الـ JSON أثناء الـ Polling
    ];

    protected $casts = [
        'is_available'       => 'boolean',
        'birth_date'         => 'date',
        'deferral_date'      => 'date',
        'last_donation_date' => 'date',
    ];

    public function scopeAvailableForDonation(Builder $query): Builder
    {
        return $query->where('is_available', true)
                     ->where('eligibility_status', 'eligible');
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getNetPointsAttribute(): int
    {
        $earned = $this->rewardTransactions()->where('type', 'earned')->sum('points');
        $redeemed = $this->rewardTransactions()->where('type', 'redeemed')->sum('points');

        return max(0, $earned - $redeemed);
    }

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

    public function rewardTransactions()
    {
        return $this->hasMany(RewardTransaction::class);
    }
}
