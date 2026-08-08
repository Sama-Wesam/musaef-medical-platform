<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\AI\SmartMatchingEngine;
use App\AI\FacilityRecommendationEngine;
use App\AI\HeatMapAnalysis;
use App\AI\EmergencyPriorityEngine;
use App\Traits\ApiResponseTrait;

class DonationCenterController extends Controller
{
    use ApiResponseTrait;

    /**
     * تبويب توصيات الذكاء الاصطناعي والطلبات المطابقة للمتبرع
     */
    public function getAiRecommendations(Request $request, SmartMatchingEngine $matchingEngine)
    {
        try {
            $donor = $request->user()->donor ?? null;
            if (!$donor) {
                return $this->errorResponse('بيانات المتبرع غير متوفرة', 404);
            }

            // جلب أحدث طلب طارئ لتطبيق خوارزمية المطابقة
            $activeRequest = BloodRequest::with(['hospital', 'bloodType'])
                ->where('status', 'pending')
                ->latest()
                ->first();

            if (!$activeRequest) {
                return $this->successResponse([], 'لا توجد طلبات نشطة حالياً');
            }

            // تشغيل محرك المطابقة الذكية بايثون
            $matches = $matchingEngine->runMatching($activeRequest, 5);

            return $this->successResponse([
                'request_details' => $activeRequest,
                'recommendations' => $matches
            ], 'تم جلب توصيات الذكاء الاصطناعي بنجاح');

        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء المعالجة: ' . $e->getMessage(), 500);
        }
    }

    /**
     * تبويب الخريطة وتحليل الخريطة الحرارية (Heat Map Analysis AI)
     */
    public function getHeatMapData(Request $request, HeatMapAnalysis $heatMapAnalysis)
    {
        try {
            // تجميع إحداثيات طلبات الدم الحالية مع مستويات الإلحاح
            $requests = BloodRequest::with('hospital')
                ->whereHas('hospital', function($q) {
                    $q->whereNotNull('latitude')->whereNotNull('longitude');
                })
                ->get()
                ->map(function($req) {
                    return [
                        'lat'       => (float)$req->hospital->latitude,
                        'lon'       => (float)$req->hospital->longitude,
                        'urgency'   => $req->emergency_level,
                        'units'     => $req->units_required,
                        'hospital'  => $req->hospital->facility_name
                    ];
                })->toArray();

            // تجميع إحداثيات المتبرعين المتاحين
            $donors = Donor::where('is_available', true)
                ->whereNotNull('latitude')->whereNotNull('longitude')
                ->get()
                ->map(function($donor) {
                    return [
                        'lat' => (float)$donor->latitude,
                        'lon' => (float)$donor->longitude
                    ];
                })->toArray();

            // توليد بيانات الخريطة الحرارية عبر بايثون
            $mapResult = $heatMapAnalysis->generateHeatMap($requests, $donors);

            return $this->successResponse([
                'heatmap_data' => $mapResult,
                'requests'     => $requests
            ], 'تم تحليل الخريطة الحرارية بنجاح');

        } catch (\Exception $e) {
            return $this->errorResponse('فشل توليد الخريطة الحرارية: ' . $e->getMessage(), 500);
        }
    }

    /**
     * تبويب جميع الطلبات مع الترتيب والفلترة الذكية (Emergency Priority AI)
     */
    public function getAllRequests(Request $request, EmergencyPriorityEngine $priorityEngine)
    {
        try {
            $sortBy = $request->query('sort_by', 'latest'); // latest أو severity

            $query = BloodRequest::with(['hospital', 'bloodType'])->latest();

            $requests = $query->get()->map(function($req) {
                return [
                    'id'             => $req->id,
                    'condition_type' => 'نزيف شديد',
                    'units_needed'   => $req->units_required,
                    'patient_age'    => 30,
                    'hospital_name'  => optional($req->hospital)->facility_name ?? 'مستشفى الشفاء',
                    'location'       => optional($req->hospital)->address ?? 'غزة',
                    'latitude'       => optional($req->hospital)->latitude,
                    'longitude'      => optional($req->hospital)->longitude,
                    'blood_type'     => optional($req->bloodType)->name ?? 'O+',
                    'emergency_level'=> $req->emergency_level,
                    'created_at'     => $req->created_at->format('Y-m-d H:i')
                ];
            })->toArray();

            // تمرير الطلبات لمحرك الأولوية والخطورة
            $sortedRequests = $priorityEngine->sortRequests($requests);

            if ($sortBy === 'latest') {
                usort($sortedRequests, function($a, $b) {
                    return strcmp($b['created_at'], $a['created_at']);
                });
            }

            return $this->successResponse($sortedRequests, 'تم جلب وترتيب الطلبات بنجاح');

        } catch (\Exception $e) {
            return $this->errorResponse('فشل جلب الطلبات: ' . $e->getMessage(), 500);
        }
    }
}
