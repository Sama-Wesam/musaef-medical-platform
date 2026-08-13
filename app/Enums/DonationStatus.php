<?php

namespace App\Enums;

enum DonationStatus: string
{
    case SUCCESSFUL = 'successful'; // تبرع ناجح
    case FAILED = 'failed';         // تبرع لم يكتمل

    /**
     * الحصول على المسمى باللغة العربية
     */
    public function label(): string
    {
        return match($this) {
            self::SUCCESSFUL => 'مكتمل بنجاح',
            self::FAILED => 'غير مكتمل',
        };
    }

    /**
     * لون الشارة (Badge Color) للعرض في الواجهات
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::SUCCESSFUL => 'success',
            self::FAILED => 'danger',
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
     * إرجاع تفاصيل الـ Enum على هيئة مصفوفة مناسبة لـ API Resources و Vue.js
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
            'badge_color' => $this->badgeColor(),
        ];
    }
}
