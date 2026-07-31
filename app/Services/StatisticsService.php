<?php

namespace App\Services;

use App\Models\Donor;
use App\Models\Hospital;
use App\Models\BloodRequest;
use App\Models\Donation;

class StatisticsService
{
    /**
     * إحصائيات لوحة تحكم المسؤول (Admin Dashboard)
     */
    public function getAdminDashboardStats(): array
    {
        return [
            'total_donors' => Donor::count(),
            'active_emergencies' => BloodRequest::whereIn('status', ['pending', 'searching'])->count(),
            'verified_hospitals' => Hospital::where('is_verified', true)->count(),
            'successful_donations' => Donation::where('status', 'successful')->count(),
        ];
    }

    /**
     * إحصائيات لوحة تحكم المستشفى (Hospital Dashboard)
     */
    public function getHospitalDashboardStats(int $hospitalId): array
    {
        return [
            'my_active_requests' => BloodRequest::where('hospital_id', $hospitalId)
                                                ->whereIn('status', ['pending', 'searching'])->count(),
            'total_blood_received' => Donation::where('hospital_id', $hospitalId)
                                              ->where('status', 'successful')->sum('units_donated'),
        ];
    }

    /**
     * إحصائيات لوحة تحكم المتبرع (Donor Dashboard)
     * تحسين الأداء عبر بناء الاستعلام مرة واحدة لاستخراج الإحصائيات
     */
    public function getDonorDashboardStats(int $donorId): array
    {
        $successfulDonations = Donation::where('donor_id', $donorId)
            ->where('status', 'successful');

        $totalDonations = $successfulDonations->count();
        $unitsDonated = $successfulDonations->sum('units_donated');

        return [
            'total_donations' => $totalDonations,
            'lives_saved'     => $unitsDonated * 3, // تقدير حسابي: كل وحدة قد تنقذ 3 أرواح
        ];
    }
}
