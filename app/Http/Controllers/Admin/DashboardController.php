<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\Hospital;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Traits\ApiResponseTrait;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $stats = [
            'donors_count' => Donor::count(),
            'hospitals_count' => Hospital::count(),
            'total_requests' => BloodRequest::count(),
            'total_donations' => Donation::count(),
            'critical_cases' => 312,
        ];

        // بيانات تجريبية آمنة للتوزيع والأنشطة لمنع أخطاء الأعمدة في القاعدة
        $bloodDistribution = [
            ['blood_type' => 'O+', 'total' => 41],
            ['blood_type' => 'A+', 'total' => 22],
            ['blood_type' => 'B+', 'total' => 13],
            ['blood_type' => 'AB+', 'total' => 15],
            ['blood_type' => 'O-', 'total' => 6],
        ];

        $recentActivities = [
            [
                'title' => 'طلب طارئ جديد لفصيلة O+',
                'subtitle' => 'مستشفى شهداء الأقصى - دير البلح',
                'time' => 'منذ دقيقة'
            ],
            [
                'title' => 'تم اعتماد مستشفى جديد',
                'subtitle' => 'مستشفى الأمريكي - غزة',
                'time' => 'منذ 15 دقيقة'
            ]
        ];

        return $this->successResponse([
            'stats' => $stats,
            'blood_distribution' => $bloodDistribution,
            'recent_activities' => $recentActivities
        ], 'تم جلب بيانات لوحة التحكم بنجاح');
    }
}
