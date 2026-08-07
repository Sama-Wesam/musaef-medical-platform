<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonorResponse extends Model
{
    protected $fillable = [
        'blood_request_id',
        'donor_id',
        'status',
        'eta_minutes',
    ];

    // الطلب المرتبط بالاستجابة
    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }

    // المتبرع صاحب الاستجابة
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
}