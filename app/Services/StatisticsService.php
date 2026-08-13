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
            'total_donors'         => Donor::count(),
            'active_emergencies'   => BloodRequest::whereIn('status', ['pending', 'searching'])->count(),
            'verified_hospitals'   => Hospital::where('is_verified', true)->count(),
            'successful_donations' => Donation::whereIn('status', ['successful', 'completed'])->count(),
        ];
    }

    /**
     * إحصائيات لوحة تحكم المستشفى (Hospital Dashboard)
     */
    public function getHospitalDashboardStats(int $hospitalId): array
    {
        return [
            'my_active_requests'   => BloodRequest::where('hospital_id', $hospitalId)
                ->whereIn('status', ['pending', 'searching'])
                ->count(),
            'total_blood_received' => (int) Donation::where('hospital_id', $hospitalId)
                ->whereIn('status', ['successful', 'completed'])
                ->sum('units_donated'),
        ];
    }

    /**
     * إحصائيات لوحة تحكم المتبرع (Donor Dashboard)
     */
    public function getDonorDashboardStats(int $donorId): array
    {
        $stats = Donation::where('donor_id', $donorId)
            ->whereIn('status', ['successful', 'completed'])
            ->selectRaw('COUNT(*) as total_donations, COALESCE(SUM(units_donated), 0) as units_donated')
            ->first();

        $units = $stats ? (int) $stats->units_donated : 0;

        return [
            'total_donations' => $stats ? (int) $stats->total_donations : 0,
            'lives_saved'     => $units * 3, // تقدير حسابي: كل وحدة تبرع تنقذ 3 أرواح
        ];
    }
}
