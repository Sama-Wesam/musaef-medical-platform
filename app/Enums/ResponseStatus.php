<?php

namespace App\Enums;

enum ResponseStatus: string
{
    case PENDING = 'pending';     // بانتظار رد المتبرع
    case ACCEPTED = 'accepted';   // المتبرع وافق وهو في الطريق
    case REJECTED = 'rejected';   // المتبرع اعتذر أو رفض
    case COMPLETED = 'completed'; // المتبرع وصل للمستشفى وأتم التبرع

    /**
     * الحصول على المسمى باللغة العربية
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'بانتظار الرد',
            self::ACCEPTED => 'تم القبول',
            self::REJECTED => 'مرفوض',
            self::COMPLETED => 'مكتمل',
        };
    }

    /**
     * لون الشارة (Badge Color) للعرض في الواجهات
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::ACCEPTED => 'primary',
            self::REJECTED => 'danger',
            self::COMPLETED => 'success',
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
