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

    // -----------------------------------------------------------------
    // العلاقات (Relationships)
    // -----------------------------------------------------------------

    // الحركات الخاصة بهذه المكافأة (من حصل عليها)
    public function transactions()
    {
        return $this->hasMany(RewardTransaction::class);
    }

    // جميع المتبرعين الذين حصلوا على هذه المكافأة عبر جدول الحركات
    public function donors()
    {
        return $this->belongsToMany(Donor::class, 'reward_transactions');
    }
}
