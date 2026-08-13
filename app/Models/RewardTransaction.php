<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'reward_id',
        'points',
        'type', // earned, redeemed
        'description',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}
