<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthInfo extends Model
{
    use HasFactory;

    protected $table = 'health_infos';

    protected $fillable = [
        'donor_id',
        'weight',
        'last_donation_date',
        'questionnaire_answers',
        'is_eligible',
    ];

    protected $casts = [
        'weight'                => 'float',
        'last_donation_date'    => 'date',
        'questionnaire_answers' => 'array',
        'is_eligible'           => 'boolean',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
}
