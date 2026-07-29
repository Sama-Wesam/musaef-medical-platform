<?php

namespace App\Services;

use App\Models\Donor;

class DonorService
{
    /**
     * جلب جميع المتبرعين.
     */
    public function getAllDonors()
    {
        return Donor::with(['user', 'bloodType'])->get();
    }

    /**
     * جلب متبرع بواسطة الـ ID.
     */
    public function getDonorById($id)
    {
        return Donor::with(['user', 'bloodType'])->find($id);
    }

    /**
     * حذف متبرع.
     */
    public function deleteDonor($id)
    {
        $donor = Donor::find($id);

        if (!$donor) {
            return false;
        }

        return $donor->delete();
    }
}
