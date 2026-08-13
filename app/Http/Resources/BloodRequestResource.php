<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\EmergencyLevel;
use App\Enums\RequestStatus;

class BloodRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // استخراج قيمة مستويات الطوارئ بحماية ودقة
        $urgencyVal = $this->emergency_level ?? $this->urgency_level;
        if ($urgencyVal instanceof EmergencyLevel) {
            $urgencyData = $urgencyVal->toArray();
        } else {
            $urgencyData = $urgencyVal ?? 'critical';
        }

        // استخراج حالة الطلب
        $statusData = $this->status instanceof RequestStatus
            ? $this->status->toArray()
            : ($this->status ?? 'pending');

        return [
            'id'             => $this->id,
            'hospital_id'    => $this->hospital_id,
            'hospital_name'  => $this->hospital->facility_name ?? $this->hospital->name ?? $this->hospital->user->name ?? 'مستشفى غير محدد',
            'address'        => $this->hospital->address ?? $this->address ?? '',
            'latitude'       => $this->hospital->latitude ?? $this->latitude ?? null,
            'longitude'      => $this->hospital->longitude ?? $this->longitude ?? null,
            'blood_type'     => $this->bloodType->name ?? $this->blood_type ?? 'N/A',
            'units_required' => $this->units_required ?? 1,
            'urgency_level'  => $urgencyData,
            'status'         => $statusData,
            'created_at'     => $this->created_at ? $this->created_at->toIso8601String() : null,
            'time_ago'       => $this->created_at ? $this->created_at->diffForHumans() : 'الآن',
        ];
    }
}
