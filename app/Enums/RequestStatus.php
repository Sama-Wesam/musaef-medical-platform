<?php

namespace App\Enums;

enum RequestStatus: string
{
    case PENDING = 'pending';     // قيد الانتظار (بانتظار موافقة الإدارة إن لزم)
    case SEARCHING = 'searching'; // جاري البحث عن متبرعين وإرسال النداءات
    case ACTIVE = 'active';       // نشط / جاري التعامل مع النداء
    case ACCEPTED = 'accepted';   // تم قبول الطلب من متبرعين
    case COMPLETED = 'completed'; // تم تلبية الطلب بالكامل
    case CANCELLED = 'cancelled'; // تم الإلغاء (لانتهاء الوقت أو لسبب آخر)

    /**
     * الحصول على المسمى باللغة العربية[cite: 27]
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'قيد الانتظار',
            self::SEARCHING, self::ACTIVE => 'جاري البحث',
            self::ACCEPTED => 'تم القبول',
            self::COMPLETED => 'مكتمل',
            self::CANCELLED => 'ملغى',
        };
    }

    /**
     * لون الشارة (Badge Color) للعرض في الواجهات
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::SEARCHING, self::ACTIVE => 'info',
            self::ACCEPTED => 'primary',
            self::COMPLETED => 'success',
            self::CANCELLED => 'secondary',
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
        ];
    }
}
