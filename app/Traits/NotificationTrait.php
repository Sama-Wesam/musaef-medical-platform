<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

trait NotificationTrait
{
    /**
     * إرسال إشعار Push Notification عبر Firebase (FCM HTTP v1 API)
     */
    public function sendPushNotification(string $deviceToken, string $title, string $body, array $data = []): bool
    {
        try {
            $projectId = config('notifications.fcm_project_id');
            $accessToken = $this->getFcmAccessToken();

            if (empty($accessToken)) {
                $serverKey = config('notifications.fcm_server_key');

                if (empty($serverKey)) {
                    Log::warning('FCM Push Notification: Neither OAuth Token nor Server Key is configured.');
                    return false;
                }

                $response = Http::withHeaders([
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type'  => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to'           => $deviceToken,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                        'sound' => 'emergency_siren.mp3',
                    ],
                    'data' => $data,
                ]);

                return $response->successful();
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
            ])->post($url, [
                'message' => [
                    'token' => $deviceToken,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                    'android' => [
                        'notification' => [
                            'sound' => 'emergency_siren.mp3',
                        ],
                    ],
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'sound' => 'emergency_siren.mp3',
                            ],
                        ],
                    ],
                    'data' => array_map('strval', $data),
                ],
            ]);

            return $response->successful();
        } catch (Exception $e) {
            Log::error('FCM Push Notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * جلب Access Token لـ Firebase v1 باستخدام Service Account
     */
    protected function getFcmAccessToken(): ?string
    {
        return config('notifications.fcm_oauth_token', null);
    }
}
