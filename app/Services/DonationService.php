<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\Donor;
use App\Events\DonationAccepted;
use App\Repositories\Contracts\BloodInventoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DonationService
{
    public function __construct(
        protected BloodInventoryRepositoryInterface $inventoryRepo
    ) {}

    public function recordDonation(array $data): Donation
    {
        return DB::transaction(function () use ($data) {
            $donorId = $data['donor_id'];
            $hospitalId = $data['hospital_id'];
            $unitsDonated = $data['units_donated'] ?? 1;

            $donor = Donor::findOrFail($donorId);
            $bloodTypeId = $data['blood_type_id'] ?? $donor->blood_type_id;

            $donation = Donation::create([
                'donor_id'         => $donorId,
                'hospital_id'      => $hospitalId,
                'blood_request_id' => $data['emergency_request_id'] ?? $data['blood_request_id'] ?? null,
                'blood_type_id'    => $bloodTypeId,
                'units_donated'    => $unitsDonated,
                'donation_date'    => now(),
                'status'           => 'completed',
                'points_earned'    => $data['points_earned'] ?? 50,
            ]);

            if ($bloodTypeId) {
                $this->inventoryRepo->updateInventory(
                    $hospitalId,
                    $bloodTypeId,
                    $unitsDonated,
                    'add'
                );
            }

            $pointsGained = $donation->points_earned;
            $donor->increment('points', $pointsGained);

            // التصحيح: استخدام اسم الجدول الصحيح health_infos بدلاً من donor_health_info
            DB::table('health_infos')->updateOrInsert(
                ['donor_id' => $donorId],
                [
                    'last_donation_date' => now()->format('Y-m-d'),
                    'updated_at'         => now(),
                ]
            );

            event(new DonationAccepted($donation));

            return $donation;
        });
    }
}
