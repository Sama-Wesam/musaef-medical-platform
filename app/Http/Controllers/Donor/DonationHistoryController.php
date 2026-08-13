<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;
use App\Models\RewardTransaction;

class DonationHistoryController extends Controller
{
    /**
     * دالة الـ index الأساسية لمعالجة طلب الـ GET القادم إلى /api/donor/history
     * تقوم باستدعاء دالة جلب البطاقة والمكافآت وسجل التبرعات مباشرة لمنع حدوث أي خطأ 500
     */
    public function index(Request $request)
    {
        return $this->getRewardsAndCard($request);
    }

    /**
     * جلب بيانات البطاقة الشاملة والشارات المستحقة الحقيقية ورسالة الذكاء الاصطناعي
     */
    public function getRewardsAndCard(Request $request)
    {
        $user = Auth::user();
        $donor = $user ? $user->donor : null;

        $completedDonationsCount = $donor ? Donation::where('donor_id', $donor->id)->where('status', 'successful')->count() : 0;
        $totalUnits = $donor ? Donation::where('donor_id', $donor->id)->where('status', 'successful')->sum('units_donated') : 0;

        $casesSupported = $totalUnits > 0 ? $totalUnits * 2 : 0;
        $pointsEarned = $completedDonationsCount * 50;

        // جلب الشارات الحقيقية التي حصل عليها المتبرع من جدول RewardTransaction وجدول rewards المرتبط
        $badges = $donor ? RewardTransaction::with('reward')
            ->where('donor_id', $donor->id)
            ->where('type', 'earned')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id'    => $transaction->reward_id ?? $transaction->id,
                    'title' => $transaction->reward->name ?? 'وسام مسعف',
                    'desc'  => $transaction->description,
                    'date'  => $transaction->created_at->format('Y-m-d'),
                    'image' => $transaction->reward->icon_path ?? 'default-badge.png'
                ];
            })->toArray() : [];

        $bloodType = $donor && $donor->bloodType ? $donor->bloodType->name : 'O+';
        $aiImpactStatement = $this->generateAiImpactMessage($bloodType, $completedDonationsCount, $casesSupported);

        $history = $donor ? Donation::with(['hospital', 'bloodType'])
            ->where('donor_id', $donor->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'date' => $item->created_at->format('Y-m-d'),
                    'hospital_name' => $item->hospital ? $item->hospital->facility_name : 'مجمع الشفاء الطبي',
                    'blood_type' => $item->bloodType ? $item->bloodType->name : 'O+',
                    'units' => $item->units_donated ?? 1,
                    'status' => $item->status == 'successful' ? 'مكتمل' : $item->status,
                    'points_earned' => 50
                ];
            }) : [];

        return response()->json([
            'status' => 'success',
            'data' => [
                'donor_code' => $donor ? 'BD' . str_pad($donor->id, 8, '0', STR_PAD_LEFT) : 'BD00000001',
                'donor_name' => $user ? $user->name : 'مستخدم مسعف',
                'blood_type' => $bloodType,
                'level' => $completedDonationsCount >= 5 ? 'متقدم' : 'متبرع مبتدئ',
                'location' => 'غزة - فلسطين',
                'units_donated' => $totalUnits,
                'cases_supported' => $casesSupported,
                'points' => $pointsEarned,
                'points_needed' => 150,
                'target_points' => 500,
                'ai_impact_statement' => $aiImpactStatement,
                'badges' => $badges,
                'donation_history' => $history
            ]
        ]);
    }

    /**
     * ⚡ دالة Polling سريعة لتحديث المكافآت والنقاط
     */
    public function liveRewardsPoll(Request $request)
    {
        $user = Auth::user();
        $donor = $user ? $user->donor : null;

        $completedDonationsCount = $donor ? Donation::where('donor_id', $donor->id)->where('status', 'successful')->count() : 0;

        return response()->json([
            'status' => 'success',
            'points' => $completedDonationsCount * 50,
            'completed_donations' => $completedDonationsCount,
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    private function generateAiImpactMessage($bloodType, $donationsCount, $casesCount)
    {
        if ($bloodType === 'O-') {
            return "بناءً على تحليلات الطوارئ والمخزون، فصيلة دمك (O-) تمثل الشريان الأكثر حرجاً لكافة الحالات العاجلة بمستشفيات غزة.";
        } elseif ($bloodType === 'O+') {
            return "بناءً على تحليلات احتياج المستشفيات، تبرعك بـ O+ يمثل شريان حياة حرج لمستشفيات شمال غزة ومجمع الشفاء.";
        } else {
            return "تحليل الأثر الذكي: تبرعاتك المستمرة ساهمت بشكل مباشر في استقرار وحدات العناية المركزية ودعم $casesCount حالة طارئة.";
        }
    }
}
