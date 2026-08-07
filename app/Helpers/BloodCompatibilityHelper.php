<?php

namespace App\Helpers;

class BloodCompatibilityHelper
{
    /**
     * خريطة من يمكنه التبرع لمن
     */
    private static $compatibilityChart = [
        'O-'  => ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'], // معطي عام
        'O+'  => ['O+', 'A+', 'B+', 'AB+'],
        'A-'  => ['A-', 'A+', 'AB-', 'AB+'],
        'A+'  => ['A+', 'AB+'],
        'B-'  => ['B-', 'B+', 'AB-', 'AB+'],
        'B+'  => ['B+', 'AB+'],
        'AB-' => ['AB-', 'AB+'],
        'AB+' => ['AB+'], // مستقبل عام
    ];

    /**
     * هل يمكن للمتبرع التبرع للمستقبل؟
     */
    public static function canDonateTo(string $donorBloodType, string $receiverBloodType): bool
    {
        return in_array($receiverBloodType, self::$compatibilityChart[$donorBloodType] ?? []);
    }

    /**
     * إرجاع قائمة بفصائل الدم التي يمكنها التبرع لفصيلة معينة
     */
    public static function getCompatibleDonorsFor(string $receiverBloodType): array
    {
        $compatibleDonors = [];
        foreach (self::$compatibilityChart as $donor => $receivers) {
            if (in_array($receiverBloodType, $receivers)) {
                $compatibleDonors[] = $donor;
            }
        }
        return $compatibleDonors;
    }
}