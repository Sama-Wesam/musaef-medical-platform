<?php

namespace App\Helpers;

class EmergencyHelper
{
    /**
     * إرجاع اللون المناسب بناءً على مستوى الطوارئ (لاستخدامه في الفرونت اند CSS)
     */
    public static function getEmergencyColorBadge(string $level): string
    {
        return match(strtolower($level)) {
            'critical' => 'danger',  // أحمر
            'high'     => 'warning', // برتقالي
            'normal'   => 'success', // أخضر
            default    => 'secondary',
        };
    }

    /**
     * حساب وقت الوصول المتوقع بالدقائق (ETA)
     */
    public static function calculateETA(float $distanceKm, float $averageSpeedKmh = 40): int
    {
        if ($distanceKm <= 0) return 0;
        
        // الوقت = (المسافة / السرعة) * 60 دقيقة
        return (int) ceil(($distanceKm / $averageSpeedKmh) * 60);
    }
}