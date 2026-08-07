<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
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
        'donation_date' => 'date',
    ];

    // المتبرع
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    // المستشفى
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    // الطلب الذي تم التبرع بناءً عليه (إن وجد)
    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }
}