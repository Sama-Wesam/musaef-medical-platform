<?php

namespace App\Services;

use App\Models\Donor;

class QRCardService
{
    public function generateDonorCard(int $donorId): ?array
    {
        $donor = Donor::with(['bloodType', 'user'])->find($donorId);

        if (!$donor) {
            return null;
        }

        $qrDataString = function_exists('generate_qr_data')
            ? generate_qr_data($donor->id, $donor->bloodType->name ?? 'Unknown')
            : json_encode(['donor_id' => $donor->id, 'blood_type' => $donor->bloodType->name ?? 'N/A']);

        return [
            'donor_name'    => $donor->user?->name ?? 'مستخدم غير معروف',
            'blood_type'    => $donor->bloodType?->name ?? 'N/A',
            'qr_code_data'  => $qrDataString,
            'last_donation' => $donor->last_donation_date ? $donor->last_donation_date->format('Y-m-d') : 'لم يتبرع بعد',
            'status'        => $donor->is_available ? 'متاح للتبرع' : 'غير متاح',
        ];
    }
}
