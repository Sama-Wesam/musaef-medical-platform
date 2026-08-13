<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\EmergencyLevel;
use App\Enums\RequestStatus;

class UrgentBloodRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // استخراج مستوى الطوارئ والتسمية النصية
        $emergencyLevel = $this->emergency_level ?? $this->urgency_level;
        $urgencyData = null;
        $urgencyLabel = 'عاجل';

        if ($emergencyLevel instanceof EmergencyLevel) {
            $urgencyData = $emergencyLevel->toArray();
            $urgencyLabel = method_exists($emergencyLevel, 'label') ? $emergencyLevel->label() : 'عاجل';
        } else {
            $urgencyData = $emergencyLevel ?? 'critical';
            $urgencyLabel = ($emergencyLevel === 'critical' || $emergencyLevel === 'حرج') ? 'حرج جداً' : 'عاجل';
        }

        // استخراج حالة الطلب
        $statusData = $this->status instanceof RequestStatus
            ? $this->status->toArray()
            : ($this->status ?? 'pending');

        return [
            'id'              => $this->id,
            'blood_type'      => $this->bloodType->name ?? $this->blood_type ?? 'غير محدد',
            'hospital_name'   => $this->hospital->facility_name ?? $this->hospital->name ?? ($this->hospital->user->name ?? 'مستشفى غير معروف'),
            'location'        => $this->hospital->address ?? ($this->city ?? 'غزة'),
            'units_needed'    => $this->units_required ?? 1,
            'emergency_level' => $urgencyData,
            'urgency_label'   => $urgencyLabel,
            'time_ago'        => $this->created_at ? $this->created_at->diffForHumans() : 'منذ فترة وجيزة',
            'status'          => $statusData,
            'created_at'      => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
