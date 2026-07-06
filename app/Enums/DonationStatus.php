<?php

namespace App\Enums;

enum DonationStatus: string
{
    case SUCCESSFUL = 'successful'; // تبرع ناجح
    case FAILED = 'failed';         // تبرع لم يكتمل
}