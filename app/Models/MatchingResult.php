<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchingResult extends Model
{
    protected $fillable = [
        'blood_request_id',
        'donor_id',
        'match_score',
        'eta_minutes',
        'is_notified',
    ];

    protected $casts = [
        'match_score' => 'decimal:2',
        'is_notified' => 'boolean',
    ];

    // الطلب
    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }

    // المتبرع المرشح
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
}
