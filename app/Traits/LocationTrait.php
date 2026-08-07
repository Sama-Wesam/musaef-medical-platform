<?php

namespace App\Traits;

trait LocationTrait
{
    /**
     * Scope لجلب السجلات القريبة من إحداثيات معينة في نطاق دائرة معينة (بالكيلومتر)
     * يمكن استخدامه هكذا: Donor::nearby($lat, $lng, 10)->get();
     */
    public function scopeNearby($query, $latitude, $longitude, $radiusKm = 10)
    {
        // استخدام معادلة Haversine لحساب المسافة داخل الاستعلام نفسه للحصول على أداء عالي
        $haversine = "(6371 * acos(cos(radians(?)) 
                        * cos(radians(latitude)) 
                        * cos(radians(longitude) - radians(?)) 
                        + sin(radians(?)) 
                        * sin(radians(latitude))))";

        return $query->selectRaw("*, {$haversine} AS distance", [$latitude, $longitude, $latitude])
                     ->whereRaw("{$haversine} < ?", [$latitude, $longitude, $latitude, $radiusKm])
                     ->orderBy('distance');
    }
}