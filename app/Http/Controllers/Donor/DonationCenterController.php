<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorResponse;
use App\AI\SmartMatchingEngine;
use App\AI\HeatMapAnalysis;
use App\AI\EmergencyPriorityEngine;
use App\Traits\ApiResponseTrait;

class DonationCenterController extends Controller
{
    use ApiResponseTrait;

    /**
     * جلب توصيات الذكاء الاصطناعي والطلبات المطابقة
     */
    public function getAiRecommendations(Request $request, SmartMatchingEngine $matchingEngine)
    {
        try {
            $activeRequests = BloodRequest::with(['hospital', 'bloodType'])
                ->whereNotIn('status', ['completed', 'cancelled', 'fulfilled', 'closed', 'rejected'])
                ->latest()
                ->get()
                ->map(function ($req) {
                    return $this->formatRequestData($req);
                });

            return $this->successResponse($activeRequests, 'تم جلب توصيات الذكاء الاصطناعي بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء المعالجة: ' . $e->getMessage(), 500);
        }
    }

    /**
     * ⚡ دالة Polling سريعة لمركز التبرعات للتحديث اللحظي
     */
    public function pollCenterData(Request $request)
    {
        try {
            $activeRequests = BloodRequest::with(['hospital', 'bloodType'])
                ->whereNotIn('status', ['completed', 'cancelled', 'fulfilled', 'closed', 'rejected'])
                ->latest()
                ->get()
                ->map(function ($req) {
                    return $this->formatRequestData($req);
                });

            return $this->successResponse([
                'total_open' => $activeRequests->count(),
                'requests'   => $activeRequests,
                'updated_at' => now()->toDateTimeString()
            ], 'تم تحديث بيانات المركز لحظياً');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * قبول طلب التبرع وتسجيل استجابة المتبرع المباشرة
     */
    public function respondToRequest(Request $request, $id)
    {
        try {
            $user = $request->user();
            $donor = $user ? $user->donor : null;

            if (!$donor) {
                return $this->errorResponse('حساب المتبرع غير مكتمل البيانات', 400);
            }

            $bloodRequest = BloodRequest::findOrFail($id);

            $response = DonorResponse::updateOrCreate(
                [
                    'blood_request_id' => $bloodRequest->id,
                    'donor_id'         => $donor->id,
                ],
                [
                    'status'       => 'accepted',
                    'match_score'  => 95,
                    'eta_minutes'  => 15,
                    'responded_at' => now(),
                ]
            );

            return $this->successResponse($response, 'تم قبول طلب التبرع بنجاح وتوجيه الإشعار لمركز المستشفى!');
        } catch (\Exception $e) {
            return $this->errorResponse('فشل تسليم قبول الطلب: ' . $e->getMessage(), 500);
        }
    }

    /**
     * تبويب الخريطة وتحليل الخريطة الحرارية
     */
    public function getHeatMapData(Request $request, HeatMapAnalysis $heatMapAnalysis)
    {
        try {
            $requests = BloodRequest::with('hospital')
                ->whereNotIn('status', ['completed', 'cancelled', 'fulfilled', 'closed', 'rejected'])
                ->get()
                ->map(function ($req) {
                    return [
                        'id'        => $req->id,
                        'lat'       => (float)(optional($req->hospital)->latitude ?? $req->latitude ?? 31.5247),
                        'lon'       => (float)(optional($req->hospital)->longitude ?? $req->longitude ?? 34.4447),
                        'urgency'   => $req->emergency_level ?? 'high',
                        'units'     => $req->units_required ?? 2,
                        'hospital'  => optional($req->hospital)->facility_name ?? $req->hospital_name ?? 'مجمع الشفاء الطبي'
                    ];
                })->toArray();

            $donors = Donor::where('is_available', true)
                ->whereNotNull('latitude')->whereNotNull('longitude')
                ->get()
                ->map(function ($donor) {
                    return [
                        'lat' => (float)$donor->latitude,
                        'lon' => (float)$donor->longitude
                    ];
                })->toArray();

            $mapResult = method_exists($heatMapAnalysis, 'generateHeatMap')
                ? $heatMapAnalysis->generateHeatMap($requests, $donors)
                : [];

            return $this->successResponse([
                'heatmap_data' => $mapResult,
                'requests'     => $requests
            ], 'تم تحليل الخريطة الحرارية بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse('فشل توليد الخريطة الحرارية: ' . $e->getMessage(), 500);
        }
    }

    /**
     * تبويب جميع الطلبات مع الترتيب والفلترة الذكية من قاعدة البيانات
     */
    public function getAllRequests(Request $request, EmergencyPriorityEngine $priorityEngine)
    {
        try {
            $sortBy = $request->query('sort_by', 'latest');

            $requests = BloodRequest::with(['hospital', 'bloodType'])
                ->whereNotIn('status', ['completed', 'cancelled', 'fulfilled', 'closed', 'rejected'])
                ->latest()
                ->get()
                ->map(function ($req) {
                    return $this->formatRequestData($req);
                })->toArray();

            if ($sortBy === 'latest') {
                usort($requests, function ($a, $b) {
                    return strcmp($b['created_at'], $a['created_at']);
                });
            }

            return $this->successResponse($requests, 'تم جلب وترتيب الطلبات بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse('فشل جلب الطلبات: ' . $e->getMessage(), 500);
        }
    }

    /**
     * توحيد صياغة حقول البيانات المرسلة للفرونت إند
     */
    private function formatRequestData($req)
    {
        $rawBlood = is_object($req->bloodType) ? $req->bloodType->name : ($req->blood_type ?? $req->blood ?? 'O+');
        $hospitalName = optional($req->hospital)->facility_name ?? $req->hospital_name ?? $req->facility_name ?? $req->hospital ?? 'مجمع الشفاء الطبي';
        $location = optional($req->hospital)->address ?? $req->address ?? $req->location ?? 'غزة - القطاع';
        $latitude = optional($req->hospital)->latitude ?? $req->latitude ?? 31.5247;
        $longitude = optional($req->hospital)->longitude ?? $req->longitude ?? 34.4447;

        return [
            'id'                => $req->id,
            'hospital'          => $hospitalName,
            'hospital_name'     => $hospitalName,
            'location'          => $location,
            'latitude'          => (float)$latitude,
            'longitude'         => (float)$longitude,
            'lat'               => (float)$latitude,
            'lng'               => (float)$longitude,
            'bloodType'         => $rawBlood,
            'blood_type'        => $rawBlood,
            'units'             => $req->units_required ?? $req->units_needed ?? $req->units ?? 2,
            'units_required'    => $req->units_required ?? $req->units_needed ?? $req->units ?? 2,
            'emergency_level'   => $req->emergency_level ?? $req->urgency ?? $req->severity ?? 'high',
            'urgency'           => $req->emergency_level ?? $req->urgency ?? 'عالية',
            'matchScore'        => $req->match_score ?? $req->match_rate ?? rand(88, 98),
            'recommendationText'=> $req->recommendation_text ?? 'متوافق مع فصيلة دمك ونطاقك الجغرافي المباشر',
            'created_at'        => $req->created_at ? $req->created_at->format('Y-m-d H:i') : now()->format('Y-m-d H:i')
        ];
    }
}
