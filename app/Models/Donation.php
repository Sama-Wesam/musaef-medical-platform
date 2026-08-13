<?php

namespace App\Models;

use App\Enums\DonationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'hospital_id',
        'blood_request_id',
        'units_donated',
        'donation_date',
        'points_earned',
        'status',
    ];

    protected $casts = [
        'units_donated' => 'integer',
        'points_earned' => 'integer',
        'donation_date' => 'date',
        'status'        => DonationStatus::class,
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }
}
