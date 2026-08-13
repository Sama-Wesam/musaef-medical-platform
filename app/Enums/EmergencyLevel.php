<?php

namespace App\Enums;

enum EmergencyLevel: string
{
    case NORMAL = 'normal';     // حالة عادية (طلب لزيادة المخزون)
    case HIGH = 'high';         // حالة طارئة (عملية جراحية قريبة)
    case CRITICAL = 'critical'; // حالة حرجة جداً (نزيف حاد، إنقاذ حياة فوري)

    /**
     * الحصول على المسمى باللغة العربية[cite: 25]
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

    /**
     * وزن الأولوية لاستخدامه في محركات ترتيب الطوارئ والذكاء الاصطناعي
     */
    public function priorityWeight(): int
    {
        return match($this) {
            self::NORMAL => 1,
            self::HIGH => 2,
            self::CRITICAL => 3,
        };
    }

    /**
     * قائمة بجميع القيم للتحقق (Validation)
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * خيارات القوائم المنسدلة لـ Vue.js
     */
    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }

    /**
     * إرجاع تفاصيل الـ Enum على هيئة مصفوفة مناسبة لـ API Resources و Vue.js
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
            'badge_color' => $this->badgeColor(),
            'weight' => $this->priorityWeight(),
        ];
    }
}
