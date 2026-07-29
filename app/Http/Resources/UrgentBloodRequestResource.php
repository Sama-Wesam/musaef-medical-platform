<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UrgentBloodRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'blood_type'     => $this->bloodType->name ?? 'غير محدد',
            'hospital_name'  => $this->hospital->facility_name ?? ($this->hospital->user->name ?? 'مستشفى غير معروف'),
            'location'       => $this->hospital->address ?? ($this->city ?? 'غزة'),
            'units_needed'   => $this->units_required,
            'urgency_label'  => 'عاجل',
            'time_ago'       => $this->created_at ? $this->created_at->diffForHumans() : 'منذ فترة وجيزة',
            'status'         => $this->status,
        ];
    }
}
