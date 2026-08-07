<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmergencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'blood_type_id' => 'required|exists:blood_types,id',
            'units_required' => 'required|integer|min:1|max:50', // لا يمكن طلب أكثر من 50 وحدة في طلب واحد لمنع التلاعب
            'emergency_level' => 'required|in:normal,high,critical',
            'description' => 'nullable|string|max:1000',
        ];
    }
}