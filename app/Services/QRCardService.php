<?php

namespace App\Services;

use App\Models\Donor;

class QRCardService
{
    public function generateDonorCard(int $donorId)
    {
        $donor = Donor::with('bloodType', 'user')->find($donorId);
        
        if (!$donor) return null;

        // استخدام الـ Helper function الذي قمنا بإنشائه سابقاً
        $qrDataString = generate_qr_data($donor->id, $donor->bloodType->name ?? 'Unknown');

        return [
            'donor_name' => $donor->user->name,
            'blood_type' => $donor->bloodType->name ?? 'N/A',
            'qr_code_data' => $qrDataString,
            'last_donation' => $donor->last_donation_date ? $donor->last_donation_date->format('Y-m-d') : 'لم يتبرع بعد',
            'status' => $donor->is_available ? 'متاح للتبرع' : 'غير متاح',
        ];
    }
}