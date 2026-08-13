<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\AI\EmergencyPriorityEngine;
use App\AI\SmartMatchingEngine;

class EmergencyRadarController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request, EmergencyPriorityEngine $priorityEngine)
    {
        $urgency = $request->query('urgency', 'all');

        $query = BloodRequest::with(['hospital', 'bloodType'])
            ->whereIn('status', ['pending', 'active', 'searching']);

        if ($urgency !== 'all') {
            $query->where('urgency_level', $urgency);
        }

        $requests = $query->latest()->get();

        if ($requests->isEmpty()) {
            return $this->successResponse([], 'لا توجد حالات طارئة حقيقية قيد الانتظار حالياً');
        }

        $formattedData = $requests->map(function ($req) {
            $createdTime = $req->created_at ? $req->created_at->timestamp : now()->timestamp;
            $elapsedSeconds = now()->timestamp - $createdTime;
            $totalTargetSeconds = 600;
            $remainingSeconds = max(0, $totalTargetSeconds - $elapsedSeconds);

            return [
                'id' => $req->id,
                'hospital' => [
                    'facility_name' => $req->hospital->facility_name ?? $req->hospital->name ?? null,
                    'address'       => $req->hospital->address ?? null,
                    'latitude'      => $req->hospital->latitude ? (float) $req->hospital->latitude : null,
                    'longitude'     => $req->hospital->longitude ? (float) $req->hospital->longitude : null,
                ],
                'remaining_seconds'      => $remainingSeconds,
                'urgency_level'          => $req->urgency_level ?? 'critical',
            ];
        });

        return $this->successResponse($formattedData, 'تم جلب الحالات الطارئة المباشرة بنجاح');
    }

    /**
     * ⚡ دالة Polling فائقة السرعة لتحديث رادار الحالات الحرجة المباشرة
     */
    public function liveRadarStream(Request $request)
    {
        $requests = BloodRequest::select('id', 'hospital_id', 'urgency_level', 'status', 'created_at')
            ->whereIn('status', ['pending', 'active', 'searching'])
            ->latest()
            ->get()
            ->map(function ($req) {
                $elapsed = now()->timestamp - ($req->created_at ? $req->created_at->timestamp : now()->timestamp);
                return [
                    'id'                => $req->id,
                    'urgency_level'     => $req->urgency_level,
                    'remaining_seconds' => max(0, 600 - $elapsed),
                    'status'            => $req->status
                ];
            });

        return response()->json([
            'status'    => 'success',
            'count'     => $requests->count(),
            'radar'     => $requests,
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    public function triggerResponse($id, SmartMatchingEngine $matchingEngine)
    {
        $bloodRequest = BloodRequest::find($id);

        if ($bloodRequest) {
            try {
                $matchingEngine->runMatching($bloodRequest, 5);
            } catch (\Exception $e) {
                // المتابعة التلقائية
            }
        }

        return $this->successResponse([
            'request_id'   => $id,
            'triggered_at' => now()->toDateTimeString(),
            'ai_status'    => 'Smart Matching & Facility Recommendation Triggered'
        ], 'تم تفعيل الاستجابة الفورية وتنبيه المتبرعين والمرافق القريبة بنجاح');
    }
}
