<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodInventory extends Model
{
    protected $fillable = [
        'hospital_id',
        'blood_type_id',
        'units_available',
        'last_updated_at',
    ];

    protected $casts = [
        'last_updated_at' => 'datetime',
    ];

    // المستشفى صاحب المخزون
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    // فصيلة الدم
    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }
}