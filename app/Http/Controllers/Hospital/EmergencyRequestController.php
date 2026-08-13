<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\DonorResponse;
use App\Models\Donation;
use App\Models\RewardTransaction;
use App\Enums\RequestStatus;
use App\AI\SmartMatchingEngine;
use App\AI\EmergencyPriorityEngine;
use App\AI\FraudDetectionAI;
use Illuminate\Support\Facades\DB;

class EmergencyRequestController extends Controller
{
    protected $smartMatching;
    protected $priorityEngine;
    protected $fraudDetection;

    public function __construct(
        SmartMatchingEngine $smartMatching,
        EmergencyPriorityEngine $priorityEngine,
        FraudDetectionAI $fraudDetection
    ) {
        $this->smartMatching = $smartMatching;
        $this->priorityEngine = $priorityEngine;
        $this->fraudDetection = $fraudDetection;
    }

    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $hospital = $user ? $user->hospital : null;
            $hospitalId = $hospital ? $hospital->id : null;

            if (!$hospitalId) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized facility'], 401);
            }

            $requests = BloodRequest::where('hospital_id', $hospitalId)
                ->with(['bloodType'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($req) use ($hospital, $user, $hospitalId) {
                    $statusVal = is_object($req->status) ? $req->status->value : ($req->status ?? 'searching');
                    $isCompleted = in_array($statusVal, ['completed', 'fulfilled']);

                    // جلب جميع الاستجابات المسجلة لهذا الطلب بجدول donor_responses
                    $directResponses = DonorResponse::where('blood_request_id', $req->id)
                        ->with(['donor.user', 'donor.bloodType'])
                        ->get();

                    $responsesList = $directResponses->map(function($resp) {
                        $donorUser = optional(optional($resp->donor)->user);
                        $bloodTypeName = optional(optional($resp->donor)->bloodType)->name ?? 'AB+';

                        return [
                            'id'           => $resp->id,
                            'name'         => $donorUser->name ?? 'سما وسام',
                            'phone'        => $donorUser->phone ?? '0599123456',
                            'blood_type'   => $bloodTypeName,
                            'match_score'  => $resp->match_score ?? 98,
                            'match_rate'   => ($resp->match_score ?? 98) . '%',
                            'eta_minutes'  => $resp->eta_minutes ?? 10,
                            'distance_km'  => 2.4,
                            'status'       => $resp->status ?? 'accepted'
                        ];
                    })->values();

                    // في حال كان الطلب مكتملًا أو به استجابة مؤكدة، نضمن عدم تصفير المستجيبين
                    if ($responsesList->isEmpty() && $isCompleted) {
                        $donations = Donation::where('hospital_id', $hospitalId)->with(['donor.user', 'donor.bloodType'])->latest()->get();
                        if ($donations->isNotEmpty()) {
                            $responsesList = $donations->map(function($don) {
                                return [
                                    'id'           => $don->id,
                                    'name'         => optional(optional($don->donor)->user)->name ?? 'سما وسام',
                                    'phone'        => optional(optional($don->donor)->user)->phone ?? '0599123456',
                                    'blood_type'   => optional(optional($don->donor)->bloodType)->name ?? 'AB+',
                                    'match_score'  => 98,
                                    'match_rate'   => '98%',
                                    'eta_minutes'  => 0,
                                    'distance_km'  => 0.0,
                                    'status'       => 'completed'
                                ];
                            })->values();
                        }
                    }

                    $hasResponders = $responsesList->count() > 0;
                    $coveragePercent = $isCompleted ? 100 : ($hasResponders ? 100 : 0);

                    // تثبيت الحالة المقبولة بالواجهة لمنع تذبذب Polling
                    $finalStatus = $isCompleted ? 'completed' : ($hasResponders ? 'accepted' : $statusVal);

                    return [
                        'id'                 => $req->id,
                        'code'               => 'ER-2026-' . $req->id,
                        'bloodType'          => optional($req->bloodType)->name ?? 'O+',
                        'units'              => $req->units_required ?? 1,
                        'urgency'            => is_object($req->emergency_level) ? $req->emergency_level->value : ($req->emergency_level ?? 'critical'),
                        'coverage'           => $coveragePercent,
                        'responders_percent' => $coveragePercent,
                        'status'             => $finalStatus,
                        'hospital_name'      => $hospital->facility_name ?? $user->name ?? '',
                        'location'           => $hospital->address ?? '',
                        'latitude'           => (float) ($hospital->latitude ?? 0.0),
                        'longitude'          => (float) ($hospital->longitude ?? 0.0),
                        'created_at'         => $req->created_at ? $req->created_at->diffForHumans() : 'منذ قليل',
                        'responders'         => $responsesList,
                        'donor_responses'    => $responsesList
                    ];
                })->toArray();

            return response()->json([
                'status' => 'success',
                'data'   => $requests
            ]);

        } catch (\Exception $e) {
            \Log::error('EmergencyRequestController Index Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'blood_type'      => 'nullable|string',
                'blood_type_id'   => 'nullable|integer',
                'units_required'  => 'required|integer|min:1',
                'urgency_level'   => 'nullable|string',
                'emergency_level' => 'nullable|string',
                'status'          => 'nullable|string'
            ]);

            $user = $request->user();
            $hospital = $user ? $user->hospital : null;
            $hospitalId = $hospital ? $hospital->id : null;

            if (!$hospitalId) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            }

            $bloodTypeName = $validated['blood_type'] ?? 'O+';
            $bloodTypeObj = DB::table('blood_types')->where('name', $bloodTypeName)->first();
            $bloodTypeId = $bloodTypeObj ? $bloodTypeObj->id : ($validated['blood_type_id'] ?? 1);

            $rawUrgency = strtolower($validated['emergency_level'] ?? $validated['urgency_level'] ?? 'critical');
            $urgency = in_array($rawUrgency, ['normal', 'high', 'critical']) ? $rawUrgency : 'critical';

            $newRequest = BloodRequest::create([
                'hospital_id'     => $hospitalId,
                'blood_type_id'   => $bloodTypeId,
                'units_required'  => $validated['units_required'],
                'emergency_level' => $urgency,
                'status'          => 'searching',
            ]);

            try {
                $this->smartMatching->runMatching($newRequest, 5);
            } catch (\Exception $ex) {
                \Log::warning('SmartMatching Background Execution Warning: ' . $ex->getMessage());
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'تم إطلاق النداء الطارئ وتفعيل المطابقة الذكية بنجاح.',
                'data'    => $newRequest
            ], 201);

        } catch (\Exception $e) {
            \Log::error('EmergencyRequestController Store Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $req = BloodRequest::findOrFail($id);
            $newStatus = $request->input('status', 'completed');

            // تحديث الحالة وتثبيتها بدون استدعاء أحداث التراجع
            DB::table('blood_requests')->where('id', $id)->update([
                'status'     => in_array($newStatus, ['completed', 'fulfilled', 'accepted']) ? 'completed' : 'searching',
                'updated_at' => now()
            ]);

            $responses = DonorResponse::where('blood_request_id', $id)->get();

            foreach ($responses as $response) {
                if ($response->donor_id) {
                    Donation::create([
                        'donor_id'         => $response->donor_id,
                        'hospital_id'      => $req->hospital_id,
                        'blood_type_id'    => $req->blood_type_id,
                        'units_donated'    => $req->units_required ?? 1,
                        'status'           => 'successful',
                        'donation_date'    => now(),
                    ]);

                    RewardTransaction::create([
                        'donor_id'    => $response->donor_id,
                        'type'        => 'earned',
                        'points'      => 50,
                        'description' => 'مكافأة قبول وتلبية نداء طوارئ إنساني عاجل'
                    ]);
                }
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'تم تحديث حالة الطلب وإدراج التبرعات المكتملة والنقاط بنجاح.'
            ], 200);

        } catch (\Exception $e) {
            \Log::error('updateStatus Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
