<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchingResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_request_id',
        'donor_id',
        'match_score',
        'eta_minutes',
        'is_notified',
    ];

    protected $casts = [
        'match_score' => 'decimal:2',
        'eta_minutes' => 'integer',
        'is_notified' => 'boolean',
    ];

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
}
