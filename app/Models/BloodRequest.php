<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    protected $fillable = [
        'hospital_id',
        'blood_type_id',
        'units_required',
        'emergency_level',
        'status',
    ];

    // المستشفى المنشئ للطلب
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    // الفصيلة المطلوبة
    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    // استجابات المتبرعين لهذا الطلب
    public function responses()
    {
        return $this->hasMany(DonorResponse::class);
    }

    // التبرعات التي تمت تلبية لهذا الطلب
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    // نتائج المطابقة الذكية المرتبطة بالطلب
    public function matchingResults()
    {
        return $this->hasMany(MatchingResult::class);
    }
}