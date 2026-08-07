<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardTransaction extends Model
{
    protected $fillable = [
        'donor_id',
        'reward_id',
        'points',
        'type', // earned, redeemed
        'description',
    ];

    // المتبرع صاحب الحركة
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    // المكافأة/الشارة المرتبطة بالحركة (إن وجدت)
    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}