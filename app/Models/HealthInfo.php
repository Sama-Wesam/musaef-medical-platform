<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthInfo extends Model
{
    protected $fillable = [
        'donor_id',
        'weight',
        'height',
        'has_chronic_diseases',
        'diseases_description',
        'is_eligible',
        'rejection_reason',
    ];

    protected $casts = [
        'has_chronic_diseases' => 'boolean',
        'is_eligible' => 'boolean',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    // علاقة البيانات الصحية بالمتبرع
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
}