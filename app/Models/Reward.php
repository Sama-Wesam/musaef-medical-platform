<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'points_required',
        'icon_path',
    ];

    protected $casts = [
        'points_required' => 'integer',
    ];

    public function transactions()
    {
        return $this->hasMany(RewardTransaction::class);
    }

    public function donors()
    {
        return $this->belongsToMany(Donor::class, 'reward_transactions');
    }
}
