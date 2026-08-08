<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;

class DonationHistoryController extends Controller
{
    /**
     * جلب بيانات البطاقة الشاملة والشارات المستحقة ورسالة الذكاء الاصطناعي
     */
    public function getRewardsAndCard(Request $request)
    {
        $user = Auth::user();
        $donor = $user ? $user->donor : null;

        // حساب إحصائيات المتبرع ديناميكياً
        $completedDonationsCount = $donor ? Donation::where('donor_id', $donor->id)->where('status', 'completed')->count() : 0;
        $totalUnits = $donor ? Donation::where('donor_id', $donor->id)->where('status', 'completed')->sum('units') : 0;

        // حساب الحالات المدعومة والنقاط المكتسبة
        $casesSupported = $totalUnits > 0 ? $totalUnits * 2 : 0;
        $pointsEarned = $completedDonationsCount * 50;

        // 1. توليد الشارات (Badges) ديناميكياً بناءً على إنجازات المتبرع الحقيقية
        $badges = [];
        if ($completedDonationsCount >= 1) {
            $badges[] = [
                'id' => 4,
                'title' => 'أول تبرع',
                'desc' => 'تم إنجاز أول تبرع بنجاح',
                'date' => '15 مارس 2024',
                'image' => 'badge-1.png'
            ];
        }
        if ($completedDonationsCount >= 5) {
            $badges[] = [
                'id' => 3,
                'title' => '5 تبرعات',
                'desc' => 'تم إنجاز 5 تبرعات بنجاح',
                'date' => '10 أبريل 2024',
                'image' => 'badge-5.png'
            ];
        }
        if ($completedDonationsCount >= 10) {
            $badges[] = [
                'id' => 2,
                'title' => '10 تبرعات',
                'desc' => 'تم إنجاز 10 تبرعات بنجاح',
                'date' => '20 مايو 2025',
                'image' => 'badge-10.png'
            ];
        }
        if ($casesSupported >= 10) {
            $badges[] = [
                'id' => 1,
                'title' => 'منقذ حياة',
                'desc' => 'ساهمت في إنقاذ أكثر من 10 حالات حرجة',
                'date' => '1 يونيو 2024',
                'image' => 'badge-hero.png'
            ];
        }

        // 2. إعداد رسالة تحفيزية مخصصة من الذكاء الاصطناعي (AI Personalized Impact Statement)
        $bloodType = $donor && $donor->bloodType ? $donor->bloodType->name : 'O+';
        $aiImpactStatement = $this->generateAiImpactMessage($bloodType, $completedDonationsCount, $casesSupported);

        // جلب سجل التبرعات
        $history = $donor ? Donation::with(['hospital', 'bloodType'])
            ->where('donor_id', $donor->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'date' => $item->created_at->format('Y-m-d'),
                    'hospital_name' => $item->hospital ? $item->hospital->name : 'مجمع الشفاء الطبي',
                    'blood_type' => $item->bloodType ? $item->bloodType->name : 'O+',
                    'units' => $item->units ?? 1,
                    'status' => $item->status == 'completed' ? 'مكتمل' : $item->status,
                    'points_earned' => 50
                ];
            }) : [];

        return response()->json([
            'status' => 'success',
            'data' => [
                'donor_code' => $donor ? 'BD' . str_pad($donor->id, 8, '0', STR_PAD_LEFT) : 'BD00000001',
                'donor_name' => $user ? $user->name : 'Sama Wesam',
                'blood_type' => $bloodType,
                'level' => $completedDonationsCount >= 5 ? 'متقدم' : 'متبرع مبتدئ',
                'location' => 'غزة - فلسطين',
                'units_donated' => $totalUnits > 0 ? $totalUnits : 8,
                'cases_supported' => $casesSupported > 0 ? $casesSupported : 12,
                'points' => $pointsEarned > 0 ? $pointsEarned : 350,
                'points_needed' => 150,
                'target_points' => 500,
                'ai_impact_statement' => $aiImpactStatement, // الرسالة الذكية
                'badges' => $badges, // الشارات الديناميكية
                'donation_history' => $history
            ]
        ]);
    }

    /**
     * توليد نص التحليل الذكي للأثر الإنساني
     */
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
