<?php

namespace App\AI;

use App\Models\BloodRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HeatMapAnalysis
{
    /**
     * استخراج إحداثيات المناطق التي تعاني من طوارئ حالياً لتمثيلها في الخريطة الحرارية
     */
    public function generateEmergencyHotspots()
    {
        // جلب الطلبات الحرجة والنشطة خلال آخر 48 ساعة
        $activeRequests = BloodRequest::join('hospitals', 'blood_requests.hospital_id', '=', 'hospitals.id')
            ->whereIn('blood_requests.status', ['pending', 'searching'])
            ->where('blood_requests.emergency_level', 'critical')
            ->where('blood_requests.created_at', '>=', Carbon::now()->subHours(48))
            ->select(
                'hospitals.latitude',
                'hospitals.longitude',
                DB::raw('SUM(blood_requests.units_required) as weight')
            )
            ->groupBy('hospitals.latitude', 'hospitals.longitude')
            ->get();

        // تجهيز الداتا للواجهة الأمامية (Google Maps Heatmap Layer)
        $hotspots = $activeRequests->map(function ($point) {
            return [
                'lat' => (float) $point->latitude,
                'lng' => (float) $point->longitude,
                'weight' => (int) $point->weight * 2 // مضاعفة الوزن للحالات الحرجة ليظهر اللون الأحمر بشكل أقوى
            ];
        });

        return $hotspots;
    }
}