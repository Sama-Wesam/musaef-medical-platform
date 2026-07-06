<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'name',
        'description',
        'points_required',
        'icon_path',
    ];

    // الحركات الخاصة بهذه المكافأة (من حصل عليها)
    public function transactions()
    {
        return $this->hasMany(RewardTransaction::class);
    }
}