<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hospital;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\ContactMessage;
use App\Http\Resources\UrgentBloodRequestResource;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    /**
     * جلب إحصائيات الصفحة الرئيسية الحقيقية
     */
    public function getHomeStats()
    {
        try {
            $donorsCount = User::where('role', 'donor')->count();
            $hospitalsCount = Hospital::count();
            $totalRequests = BloodRequest::count();
            $supportedCases = Donation::where('status', 'successful')->count();

            if ($supportedCases === 0) {
                $supportedCases = BloodRequest::whereIn('status', ['completed', 'fulfilled'])->count();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'donors_count'    => $donorsCount,
                    'hospitals_count' => $hospitalsCount,
                    'total_requests'  => $totalRequests,
                    'supported_cases' => $supportedCases,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإحصائيات',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب أحدث الحالات الطارئة العاجلة
     */
    public function getUrgentRequests()
    {
        try {
            $urgentRequests = BloodRequest::with(['hospital.user', 'bloodType'])
                ->whereIn('emergency_level', ['high', 'critical'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            return response()->json([
                'success' => true,
                'data' => UrgentBloodRequestResource::collection($urgentRequests)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الحالات الطارئة',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب قائمة المستشفيات والجهات الشريكة لصفحة من نحن
     */
    public function getPartnersHospitals()
    {
        try {
            $hospitals = Hospital::with('user')
                ->where('is_verified', true)
                ->get()
                ->map(function ($hospital) {
                    return [
                        'id' => $hospital->id,
                        'name' => $hospital->facility_name ?? ($hospital->user->name ?? 'مستشفى معتمد'),
                        'address' => $hospital->address,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $hospitals
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في جلب قائمة الشركاء'
            ], 500);
        }
    }

    /**
     * استقبال رسائل تواصل معنا وتخزينها
     */
    public function sendContactMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            ContactMessage::create([
                'name'    => $request->name,
                'email'   => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رسالتك بنجاح، وسنقوم بالرد عليك في أقرب وقت!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل إرسال الرسالة، يرجى المحاولة لاحقاً.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
