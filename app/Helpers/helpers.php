<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

if (! function_exists('format_phone_number')) {
    /**
     * تنظيف وتنسيق رقم الهاتف ليكون جاهزاً لإرسال الـ SMS
     */
    function format_phone_number(string $phone): string
    {
        // إزالة أي رموز غير رقمية مع الإبقاء على الأرقام فقط
        return preg_replace('/[^0-9]/', '', $phone);
    }
}

if (! function_exists('send_emergency_sms')) {
    /**
     * دالة عامة لإرسال رسائل نصية قصيرة مع دعم مزودي الخدمة وتفادي الانقطاع
     */
    function send_emergency_sms(string $phone, string $message): bool
    {
        try {
            $formattedPhone = format_phone_number($phone);
            $smsProviderUrl = env('SMS_PROVIDER_URL');
            $apiKey = env('SMS_API_KEY');

            // 1. الاتصال بمزود الخدمة بحال توفر الإعدادات في .env
            if ($smsProviderUrl && $apiKey) {
                $response = Http::timeout(3)->post($smsProviderUrl, [
                    'api_key' => $apiKey,
                    'to'      => $formattedPhone,
                    'message' => $message,
                ]);

                if ($response->successful()) {
                    Log::info("SMS successfully sent via API to {$formattedPhone}");
                    return true;
                }
            }

            // 2. تسجيل العملية عند عدم تفعيل بيئة الترسيل الخارجي (Dev Environment)
            Log::info("SMS Simulation to {$formattedPhone}: {$message}");
            return true;

        } catch (\Throwable $e) {
            Log::error("Failed to send SMS to {$phone}: " . $e->getMessage());
            return false;
        }
    }
}

if (! function_exists('generate_qr_data')) {
    /**
     * إنشاء الداتا التي سيتم تخزينها في بطاقة المتبرع (QR Code) مع التوقيع الرقمي لمنع التزوير
     */
    function generate_qr_data($donorId, string $bloodType): string
    {
        $timestamp = now()->timestamp;
        $appKey = config('app.key', 'MusaefSecureSecretKey');

        $payload = [
            'musaef_id'  => $donorId,
            'blood_type' => $bloodType,
            'timestamp'  => $timestamp,
        ];

        // إضافة توقيع مشفر (Digital HMAC Signature) لمنع التلاعب والتزوير عند المسح
        $payload['hash'] = hash_hmac('sha256', $donorId . $bloodType . $timestamp, $appKey);

        return json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
}

if (! function_exists('verify_qr_data')) {
    /**
     * التحقق من صحة التوقيع الرقمي لبطاقة QR لمنع التزوير عند المسح داخل المستشفى
     */
    function verify_qr_data(string $jsonPayload): bool
    {
        $data = json_decode($jsonPayload, true);
        if (!$data || !isset($data['musaef_id'], $data['blood_type'], $data['timestamp'], $data['hash'])) {
            return false;
        }

        $appKey = config('app.key', 'MusaefSecureSecretKey');
        $expectedHash = hash_hmac('sha256', $data['musaef_id'] . $data['blood_type'] . $data['timestamp'], $appKey);

        return hash_equals($expectedHash, $data['hash']);
    }
}
