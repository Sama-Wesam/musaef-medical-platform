<?php

namespace App\Enums;

enum NotificationType: string
{
    case EMERGENCY = 'emergency'; // نداء طوارئ للمتبرعين
    case INFO = 'info';           // معلومات عامة (مثل قبول متبرع لطلب)
    case REWARD = 'reward';       // إشعارات نظام المكافآت والنقاط
    case SYSTEM = 'system';       // إشعارات النظام (تحذير نقص مخزون، تقارير)

    /**
     * الحصول على المسمى باللغة العربية
     */
    public function label(): string
    {
        return match($this) {
            self::EMERGENCY => 'نداء طوارئ',
            self::INFO => 'تنبيه معلوماتي',
            self::REWARD => 'مكافآت ونقاط',
            self::SYSTEM => 'إشعار نظام',
        };
    }

    /**
     * لون الشارة (Badge Color) للعرض في الواجهات
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::EMERGENCY => 'danger',
            self::INFO => 'info',
            self::REWARD => 'success',
            self::SYSTEM => 'secondary',
        };
    }
}
