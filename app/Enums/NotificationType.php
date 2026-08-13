<?php

namespace App\Enums;

enum NotificationType: string
{
    case EMERGENCY = 'emergency';         // نداء طوارئ للمتبرعين
    case REQUEST_ACCEPTED = 'accepted';   // قبول طلب التبرع
    case REQUEST_REJECTED = 'rejected';   // رفض طلب التبرع
    case STATUS_UPDATE = 'status_update'; // تحديث حالة الطلبات
    case MEDICAL_ALERT = 'medical_alert'; // التنبيهات والإعلانات الطبية
    case REWARD = 'reward';               // إشعارات نظام المكافآت والنقاط
    case SYSTEM = 'system';               // إشعارات النظام (تحذير نقص مخزون، تقارير)

    /**
     * الحصول على المسمى باللغة العربية
     */
    public function label(): string
    {
        return match($this) {
            self::EMERGENCY => 'نداء طوارئ',
            self::REQUEST_ACCEPTED => 'تم قبول طلب التبرع',
            self::REQUEST_REJECTED => 'تم رفض طلب التبرع',
            self::STATUS_UPDATE => 'تحديث حالة الطلب',
            self::MEDICAL_ALERT => 'تنبيه طبي',
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
            self::REQUEST_ACCEPTED => 'success',
            self::REQUEST_REJECTED => 'danger',
            self::STATUS_UPDATE => 'info',
            self::MEDICAL_ALERT => 'warning',
            self::REWARD => 'success',
            self::SYSTEM => 'secondary',
        };
    }

    /**
     * رمز الأيقونة المقابل لنوع الإشعار
     */
    public function icon(): string
    {
        return match($this) {
            self::EMERGENCY => 'alert-circle',
            self::REQUEST_ACCEPTED => 'check-circle',
            self::REQUEST_REJECTED => 'x-circle',
            self::STATUS_UPDATE => 'refresh-cw',
            self::MEDICAL_ALERT => 'bell',
            self::REWARD => 'award',
            self::SYSTEM => 'info',
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
            'icon' => $this->icon(),
        ];
    }
}
