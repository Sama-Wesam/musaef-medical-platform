<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait NotificationTrait
{
    /**
     * إرسال إشعار Firebase Push Notification (FCM)
     */
    public function sendPushNotification(string $deviceToken, string $title, string $body, array $data = []): bool
    {
        try {
            $serverKey = config('notifications.fcm_server_key'); // يجب إضافته في config

            $payload = [
                "to" => $deviceToken,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                    "sound" => "emergency_siren.mp3", // صوت مخصص للطوارئ
                ],
                "data" => $data
            ];

            // كود مبسط للاتصال بـ Firebase API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: key=' . $serverKey,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            $result = curl_exec($ch);
            curl_close($ch);

            return true;
        } catch (\Exception $e) {
            Log::error('FCM Push Notification failed: ' . $e->getMessage());
            return false;
        }
    }
}