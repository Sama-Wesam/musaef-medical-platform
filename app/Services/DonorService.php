<?php

namespace App\Services;

use App\Models\Donor;
use Illuminate\Database\Eloquent\Collection;

class DonorService
{
    public function getAllDonors(): Collection
    {
        return Donor::with(['user', 'bloodType', 'healthInfo'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getDonorById(int $id): ?Donor
    {
        // استخدام withCount و withSum لرفع الأداء الفائق في استعلام واحد بدلاً من الاستعلامات المنفصلة
        $donor = Donor::with(['user', 'bloodType', 'healthInfo'])
            ->withCount(['donations as total_donations_count' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->withSum(['donations as total_units_donated' => function ($query) {
                $query->where('status', 'completed');
            }], 'units_donated')
            ->find($id);

        if (!$donor) {
            return null;
        }

        $donor->total_units_donated = (int) ($donor->total_units_donated ?? 0);

        return $donor;
    }

    public function deleteDonor(int $id): bool
    {
        $donor = Donor::find($id);

        if (!$donor) {
            return false;
        }

        return (bool) $donor->delete();
    }
}
