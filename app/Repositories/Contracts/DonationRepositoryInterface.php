<?php

namespace App\Repositories\Contracts;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Collection;

interface DonationRepositoryInterface
{
    public function getDonationsByDonor(int $donorId): Collection;
    public function getDonationsByHospital(int $hospitalId): Collection;
    public function createDonation(array $data): Donation;
    public function getTotalPointsForDonor(int $donorId): int;
}
