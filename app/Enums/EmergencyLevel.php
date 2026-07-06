<?php

namespace App\Enums;

enum EmergencyLevel: string
{
    case NORMAL = 'normal';     // حالة عادية (طلب لزيادة المخزون)
    case HIGH = 'high';         // حالة طارئة (عملية جراحية قريبة)
    case CRITICAL = 'critical'; // حالة حرجة جداً (نزيف حاد، إنقاذ حياة فوري)
}