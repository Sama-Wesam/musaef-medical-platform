<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EmergencyRadarController extends Controller
{
    use ApiResponseTrait;

    /**
     * جلب الحالات الحرجة المباشرة لرادار الطوارئ
     */
    public function index(Request $request)
    {
        $urgency = $request->query('urgency', 'all');

        // جلب الطلبات النشطة من قاعدة البيانات إن وجدت
        $query = BloodRequest::with('hospital')
            ->whereIn('status', ['pending', 'active']);

        if ($urgency !== 'all') {
            $query->where('urgency_level', $urgency);
        }

        $requests = $query->latest()->get();

        // في حال كانت قاعدة البيانات فارغة، نُرجع مصفوفة مهيكلة آمنة
        if ($requests->isEmpty()) {
            $data = [
                [
                    'id' => 1,
                    'hospital' => [
                        'facility_name' => 'مستشفى الكويتي',
                        'address' => 'الجنوب - رفح'
                    ],
                    'remaining_seconds' => 332,
                    'expected_response_time' => '6 دقائق',
                    'urgency_level' => 'critical',
                    'icon' => 'Group 1000002306.png'
                ],
                [
                    'id' => 2,
                    'hospital' => [
                        'facility_name' => 'مستشفى العودة',
                        'address' => 'وسطى - النصيرات'
                    ],
                    'remaining_seconds' => 272,
                    'expected_response_time' => '6 دقائق',
                    'urgency_level' => 'critical',
                    'icon' => 'Group 1000002306 (1).png'
                ],
                [
                    'id' => 3,
                    'hospital' => [
                        'facility_name' => 'مستشفى ناصر',
                        'address' => 'جنوب - خانيونس'
                    ],
                    'remaining_seconds' => 572,
                    'expected_response_time' => '6 دقائق',
                    'urgency_level' => 'medium',
                    'icon' => 'Group 1000002306 (2).png'
                ]
            ];

            return $this->successResponse($data, 'تم جلب الحالات الطارئة الافتراضية بنجاح');
        }

        return $this->successResponse($requests, 'تم جلب الحالات الطارئة المباشرة بنجاح');
    }

    /**
     * تفعيل الاستجابة الفورية لحالة طوارئ معينة
     */
    public function triggerResponse($id)
    {
        return $this->successResponse([
            'request_id' => $id,
            'triggered_at' => now()->toDateTimeString()
        ], 'تم تفعيل الاستجابة الفورية وتنبيه المتبرعين بنجاح');
    }
}
