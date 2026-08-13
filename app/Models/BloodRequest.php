<?php

namespace App\Models;

use App\Enums\EmergencyLevel;
use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $table = 'blood_requests';

    protected $fillable = [
        'hospital_id',
        'blood_type_id',
        'units_required',
        'emergency_level',
        'status',
    ];

    protected $casts = [
        'hospital_id'     => 'integer',
        'blood_type_id'   => 'integer',
        'units_required'  => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['searching', 'pending', 'active', 'open']);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    public function responses()
    {
        return $this->hasMany(DonorResponse::class, 'blood_request_id');
    }

    public function donorResponses()
    {
        return $this->hasMany(DonorResponse::class, 'blood_request_id');
    }

    public function responders()
    {
        return $this->hasMany(DonorResponse::class, 'blood_request_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function matchingResults()
    {
        return $this->hasMany(MatchingResult::class);
    }
}
