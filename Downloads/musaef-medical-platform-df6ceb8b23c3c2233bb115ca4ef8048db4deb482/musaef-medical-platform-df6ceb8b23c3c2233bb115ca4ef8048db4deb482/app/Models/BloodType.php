<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
{
    protected $fillable = [
        'name',
        'is_rare',
    ];

    protected $casts = [
        'is_rare' => 'boolean',
    ];

    // علاقة الفصيلة بالمتبرعين (الفصيلة يمتلكها عدة متبرعين)
    public function donors()
    {
        return $this->hasMany(Donor::class);
    }

    // علاقة الفصيلة بطلبات الطوارئ
    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }

    // علاقة الفصيلة بمخزون المستشفيات
    public function bloodInventories()
    {
        return $this->hasMany(BloodInventory::class);
    }
}