<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmergencyHelper
{
    /**
     * إرجاع اللون المناسب بناءً على مستوى الطوارئ (لاستخدامه في الواجهة)
     */
    public static function getEmergencyColorBadge(string $level): string
    {
        return match(strtolower(trim($level))) {
            'critical', 'حرج', 'حرجة جداً' => 'danger',  // أحمر
            'high', 'عالي', 'طارئة'         => 'warning', // برتقالي
            'normal', 'عادي', 'عادية'       => 'success', // أخضر
            default                         => 'secondary',
        };
    }

    /**
     * حساب وقت الوصول المتوقع بالدقائق (ETA) اعتماداً على Google Maps API مع Fallback
     */
    public static function calculateETA(
        float $distanceKm,
        float $averageSpeedKmh = 40,
        ?float $originLat = null,
        ?float $originLng = null,
        ?float $destLat = null,
        ?float $destLng = null
    ): int {
        if ($distanceKm <= 0) return 0;

        $apiKey = config('services.google.maps_api_key', env('GOOGLE_MAPS_API_KEY'));

        // 1. استخدام Google Maps Distance Matrix API بحال توفرت الإحداثيات والمفتاح
        if ($apiKey && $originLat && $originLng && $destLat && $destLng) {
            try {
                $response = Http::timeout(2)->get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                    'origins'      => "{$originLat},{$originLng}",
                    'destinations' => "{$destLat},{$destLng}",
                    'mode'         => 'driving',
                    'key'          => $apiKey,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['rows'][0]['elements'][0]['duration']['value'])) {
                        // تحويل الثواني إلى دقائق
                        return (int) ceil($data['rows'][0]['elements'][0]['duration']['value'] / 60);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("Google Maps ETA calculation failed, using fallback formula: {$e->getMessage()}");
            }
        }

        // 2. المعادلة الرياضية الاحتياطية (Fallback Mechanism)
        return (int) ceil(($distanceKm / max($averageSpeedKmh, 1)) * 60);
    }
}
