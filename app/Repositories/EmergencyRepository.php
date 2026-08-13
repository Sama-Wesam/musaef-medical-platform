<?php

namespace App\Repositories;

use App\Enums\RequestStatus;
use App\Models\BloodRequest;
use App\Repositories\Contracts\EmergencyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EmergencyRepository implements EmergencyRepositoryInterface
{
    /**
     * جلب كافة طلبات الطوارئ النشطة مرتبة حسب الأولوية الفعليّة
     */
    public function getActiveEmergencies(): Collection
    {
        $searching = defined('App\Enums\RequestStatus::SEARCHING') ? RequestStatus::SEARCHING->value : 'searching';
        $pending   = defined('App\Enums\RequestStatus::PENDING') ? RequestStatus::PENDING->value : 'pending';

        return BloodRequest::with(['hospital.user', 'bloodType'])
            ->whereIn('status', [$searching, $pending])
            ->orderByRaw("FIELD(emergency_level, 'critical', 'high', 'normal')")
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRequestsByHospital(int $hospitalId): Collection
    {
        return BloodRequest::with('bloodType')
            ->where('hospital_id', $hospitalId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findById(int $id): ?BloodRequest
    {
        return BloodRequest::with(['hospital', 'bloodType', 'responses'])->find($id);
    }

    public function createRequest(array $data): BloodRequest
    {
        return BloodRequest::create($data);
    }

    /**
     * تحديث حالة طلب الطوارئ باستخدام الاستعلام المباشر لرفع الأداء الفائق وتجنب تحميل العلاقات غير الضرورية
     */
    public function updateStatus(int $id, string $status): bool
    {
        $updatedRows = BloodRequest::where('id', $id)
            ->update(['status' => $status]);

        return $updatedRows > 0;
    }
}
