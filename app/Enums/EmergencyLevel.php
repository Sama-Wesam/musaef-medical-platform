<?php

namespace App\Enums;

enum EmergencyLevel: string
{
    case NORMAL = 'normal';     // حالة عادية (طلب لزيادة المخزون)
    case HIGH = 'high';         // حالة طارئة (عملية جراحية قريبة)
    case CRITICAL = 'critical'; // حالة حرجة جداً (نزيف حاد، إنقاذ حياة فوري)

    /**
     * الحصول على المسمى باللغة العربية
     */
    public function label(): string
    {
        return match($this) {
            self::NORMAL => 'عادية',
            self::HIGH => 'طارئة',
            self::CRITICAL => 'حرجة جداً',
        };
    }

    /**
     * لون الشارة (Badge Color) للعرض في الواجهات
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::NORMAL => 'info',
            self::HIGH => 'warning',
            self::CRITICAL => 'danger',
        };
    }
}
