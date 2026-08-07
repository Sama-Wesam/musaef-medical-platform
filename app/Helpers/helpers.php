<?php

use Illuminate\Support\Facades\Log;

if (! function_exists('format_phone_number')) {
    /**
     * تنظيف وتنسيق رقم الهاتف ليكون جاهزاً لإرسال الـ SMS
     */
    function format_phone_number($phone)
    {
        // إزالة أي رموز غير رقمية أو علامة +
        return preg_replace('/[^0-9]/', '', $phone);
    }
}

if (! function_exists('send_emergency_sms')) {
    /**
     * دالة عامة لإرسال رسائل نصية قصيرة في وقت انقطاع الإنترنت (SMS API)
     */
    function send_emergency_sms($phone, $message)
    {
        try {
            // هنا يتم وضع كود الربط مع مزود خدمة الـ SMS (مثل Twilio أو غيره)
            // Http::post('sms-api-url', ['phone' => $phone, 'msg' => $message]);
            
            Log::info("SMS sent to {$phone}: {$message}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send SMS to {$phone}: " . $e->getMessage());
            return false;
        }
    }
}

if (! function_exists('generate_qr_data')) {
    /**
     * إنشاء الداتا التي سيتم تخزينها في بطاقة المتبرع (QR Code)
     */
    function generate_qr_data($donorId, $bloodType)
    {
        return json_encode([
            'musaef_id' => $donorId,
            'blood_type' => $bloodType,
            'timestamp' => now()->timestamp
        ]);
    }
}