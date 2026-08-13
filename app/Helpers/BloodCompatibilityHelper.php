<?php

namespace App\Helpers;

class BloodCompatibilityHelper
{
    /**
     * خريطة التوافق الحيوي الكاملة لفصائل الدم (من يمكنه التبرع لمن)
     */
    private static array $compatibilityChart = [
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
        $donor = strtoupper(trim($donorBloodType));
        $receiver = strtoupper(trim($receiverBloodType));

        return in_array($receiver, self::$compatibilityChart[$donor] ?? [], true);
    }

    /**
     * إرجاع قائمة بالفصائل التي يمكنها التبرع لفصيلة المستقبل
     */
    public static function getCompatibleDonorsFor(string $receiverBloodType): array
    {
        $receiver = strtoupper(trim($receiverBloodType));
        $compatibleDonors = [];

        foreach (self::$compatibilityChart as $donor => $receivers) {
            if (in_array($receiver, $receivers, true)) {
                $compatibleDonors[] = $donor;
            }
        }

        return $compatibleDonors;
    }

    /**
     * إرجاع قائمة بالفصائل التي يمكن لفصيلة المتبرع إعطاؤها
     */
    public static function getCompatibleReceiversFor(string $donorBloodType): array
    {
        $donor = strtoupper(trim($donorBloodType));
        return self::$compatibilityChart[$donor] ?? [];
    }
}
