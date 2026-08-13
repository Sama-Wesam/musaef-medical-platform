<?php

namespace App\Repositories;

use App\Models\Donation;
use App\Repositories\Contracts\DonationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DonationRepository implements DonationRepositoryInterface
{
    public function getDonationsByDonor(int $donorId): Collection
    {
        return Donation::with(['hospital.user', 'bloodRequest'])
            ->where('donor_id', $donorId)
            ->orderBy('donation_date', 'desc')
            ->get();
    }

    public function getDonationsByHospital(int $hospitalId): Collection
    {
        return Donation::with(['donor.user', 'donor.bloodType'])
            ->where('hospital_id', $hospitalId)
            ->orderBy('donation_date', 'desc')
            ->get();
    }

    public function createDonation(array $data): Donation
    {
        return Donation::create($data);
    }

    public function getTotalPointsForDonor(int $donorId): int
    {
        return (int) Donation::where('donor_id', $donorId)
            ->where('status', 'successful')
            ->sum('points_earned');
    }
}
