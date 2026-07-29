<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalGuidelineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image_path' => $this->image_path,
            // نرسل مقتطفاً صغيراً أول 150 حرفاً بدلاً من النص الكامل لتوفير البيانات
            'excerpt' => mb_substr(strip_tags($this->content), 0, 150) . '...',
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
