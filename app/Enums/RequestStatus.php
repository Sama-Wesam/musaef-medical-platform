<?php

namespace App\Enums;

enum RequestStatus: string
{
    case PENDING = 'pending';     // قيد الانتظار (بانتظار موافقة الإدارة إن لزم)
    case SEARCHING = 'searching'; // جاري البحث عن متبرعين وإرسال النداءات
    case ACCEPTED = 'accepted';   // تم قبول الطلب من متبرعين
    case COMPLETED = 'completed'; // تم تلبية الطلب بالكامل
    case CANCELLED = 'cancelled'; // تم الإلغاء (لانتهاء الوقت أو لسبب آخر)

    /**
     * الحصول على المسمى باللغة العربية
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'قيد الانتظار',
            self::SEARCHING => 'جاري البحث',
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
            self::SEARCHING => 'info',
            self::ACCEPTED => 'primary',
            self::COMPLETED => 'success',
            self::CANCELLED => 'secondary',
        };
    }
}
