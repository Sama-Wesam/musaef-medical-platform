<?php

namespace App\Services;

use App\Models\Donor;
use App\Models\Hospital;
use App\Models\BloodRequest;
use App\Models\Donation;

class StatisticsService
{
    public function getAdminDashboardStats()
    {
        return [
            'total_donors' => Donor::count(),
            'active_emergencies' => BloodRequest::whereIn('status', ['pending', 'searching'])->count(),
            'verified_hospitals' => Hospital::where('is_verified', true)->count(),
            'successful_donations' => Donation::where('status', 'successful')->count(),
        ];
    }

    public function getHospitalDashboardStats(int $hospitalId)
    {
        return [
            'my_active_requests' => BloodRequest::where('hospital_id', $hospitalId)
                                                ->whereIn('status', ['pending', 'searching'])->count(),
            'total_blood_received' => Donation::where('hospital_id', $hospitalId)
                                              ->where('status', 'successful')->sum('units_donated'),
        ];
    }

    public function getDonorDashboardStats(int $donorId)
    {
        return [
            'total_donations' => Donation::where('donor_id', $donorId)->where('status', 'successful')->count(),
            'lives_saved' => Donation::where('donor_id', $donorId)->where('status', 'successful')->sum('units_donated') * 3, // كل وحدة قد تنقذ 3 أرواح
        ];
    }
}