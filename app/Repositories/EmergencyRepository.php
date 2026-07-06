<?php

namespace App\Repositories;

use App\Models\BloodRequest;
use Illuminate\Database\Eloquent\Collection;

class EmergencyRepository
{
    public function getActiveEmergencies(): Collection
    {
        // جلب الطلبات التي ما زالت قيد البحث أو الانتظار
        return BloodRequest::with(['hospital.user', 'bloodType'])
            ->whereIn('status', ['pending', 'searching'])
            ->orderBy('emergency_level', 'desc') // ترتيب بحيث تظهر الحالات الحرجة (critical) أولاً
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

    public function updateStatus(int $id, string $status): bool
    {
        $request = $this->findById($id);
        if ($request) {
            return $request->update(['status' => $status]);
        }
        return false;
    }
}