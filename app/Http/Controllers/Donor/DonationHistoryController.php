<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Models\Donation;

class DonationHistoryController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $donor = $request->user()->donor ?? null;

        if (!$donor) {
            return $this->notFoundResponse('بيانات المتبرع غير موجودة');
        }

        // جلب سجل تبرعات المتبرع
        $history = Donation::with(['bloodRequest.hospital', 'bloodType'])
            ->where('donor_id', $donor->id)
            ->latest()
            ->get()
            ->map(function ($donation) {
                return [
                    'date'          => $donation->created_at->format('Y-m-d'),
                    'hospital_name' => optional(optional($donation->bloodRequest)->hospital)->facility_name ?? 'مستشفى الشفاء الطبي',
                    'blood_type'    => optional($donation->bloodType)->name ?? 'O+',
                    'units'         => $donation->units_donated ?? 1,
                    'status'        => $donation->status === 'successful' ? 'مكتمل' : 'عاجلة',
                    'points_earned' => $donation->points_earned ?? 50
                ];
            });

        return $this->successResponse([
            'donor_code'      => 'BD' . str_pad($donor->id, 8, '0', STR_PAD_LEFT),
            'level'           => $donor->level ?? 'متبرع نشط',
            'status_text'     => 'متبرع نشط',
            'location'        => $donor->address ?? 'غزة - فلسطين',
            'units_donated'   => $donor->donations()->where('status', 'successful')->sum('units_donated') ?? 8,
            'cases_supported' => $donor->donations()->where('status', 'successful')->count() ?? 12,
            'points'          => $donor->points ?? 350,
            'points_progress' => 70,
            'points_needed'   => 150,
            'target_points'   => 500,
            'donation_history'=> $history
        ], 'تم جلب سجل التبرعات والإنجازات بنجاح');
    }
}
