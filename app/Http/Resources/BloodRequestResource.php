<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BloodRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hospital_id' => $this->hospital_id,
            'urgency_level' => $this->urgency_level ?? 'حارجة',
            'hospital_name' => $this->hospital->facility_name ?? $this->hospital->user->name ?? 'مستشفى غير محدد',
            'address' => $this->hospital->address ?? '',
            'latitude' => $this->hospital->latitude ?? null,
            'longitude' => $this->hospital->longitude ?? null,
            'blood_type' => $this->bloodType->name ?? $this->blood_type ?? '',
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
