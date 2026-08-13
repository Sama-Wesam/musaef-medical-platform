<?php

namespace App\Helpers;

class DistanceHelper
{
    /**
     * حساب المسافة بين نقطتين جغرافيتين مع حماية الدقة العشرية
     * $unit: 'K' = كيلومتر، 'M' = أميال، 'N' = ميل بحري
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2, string $unit = 'K'): float
    {
        if (($lat1 === $lat2) && ($lon1 === $lon2)) {
            return 0.0;
        }

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

        // حماية من أخطاء acos مع الأرقام العشرية المتناهية في الصغر للحد من خطأ NAN
        $dist = min(1.0, max(-1.0, $dist));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit === "K") {
            return round($miles * 1.609344, 2); // النتيجة بالكيلومتر
        } elseif ($unit === "N") {
            return round($miles * 0.8684, 2); // ميل بحري
        }

        return round($miles, 2); // أميال
    }
}
