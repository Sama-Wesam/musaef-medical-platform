<?php

namespace App\Enums;

enum RequestStatus: string
{
    case PENDING = 'pending';     // قيد الانتظار (بانتظار موافقة الإدارة إن لزم)
    case SEARCHING = 'searching'; // جاري البحث عن متبرعين وإرسال النداءات
    case ACCEPTED = 'accepted';   // تم قبول الطلب من متبرعين
    case COMPLETED = 'completed'; // تم تلبية الطلب بالكامل
    case CANCELLED = 'cancelled'; // تم الإلغاء (لانتهاء الوقت أو لسبب آخر)
}