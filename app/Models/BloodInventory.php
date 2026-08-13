<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodInventory extends Model
{
    use HasFactory;

    protected $table = 'blood_inventories';

    protected $fillable = [
        'hospital_id',
        'blood_type_id',
        'units_available',
        'min_threshold',
    ];

    protected $casts = [
        'hospital_id'     => 'integer',
        'blood_type_id'   => 'integer',
        'units_available' => 'integer',
        'min_threshold'   => 'integer',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }
}
