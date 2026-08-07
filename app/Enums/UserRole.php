<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';       // مدير النظام
    case DONOR = 'donor';       // المتبرع
    case HOSPITAL = 'hospital'; // المستشفى أو بنك الدم
}