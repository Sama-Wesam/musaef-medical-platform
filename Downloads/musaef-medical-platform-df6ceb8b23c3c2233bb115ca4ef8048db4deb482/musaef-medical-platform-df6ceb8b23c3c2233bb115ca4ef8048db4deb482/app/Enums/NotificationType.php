<?php

namespace App\Enums;

enum NotificationType: string
{
    case EMERGENCY = 'emergency'; // نداء طوارئ للمتبرعين
    case INFO = 'info';           // معلومات عامة (مثل قبول متبرع لطلب)
    case REWARD = 'reward';       // إشعارات نظام المكافآت والنقاط
    case SYSTEM = 'system';       // إشعارات النظام (تحذير نقص مخزون، تقارير)
}