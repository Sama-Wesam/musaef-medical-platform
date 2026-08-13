<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';       // مدير النظام
    case DONOR = 'donor';       // المتبرع
    case HOSPITAL = 'hospital'; // المستشفى أو بنك الدم

    /**
     * الحصول على المسمى باللغة العربية
     */
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'مدير النظام',
            self::DONOR => 'متبرع',
            self::HOSPITAL => 'جهة طبية / مستشفى',
        };
    }

    /**
     * لون الشارة (Badge Color) للعرض في الواجهات
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::ADMIN => 'danger',
            self::DONOR => 'success',
            self::HOSPITAL => 'primary',
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
