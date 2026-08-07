<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AIAnalysisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hospital_id' => 'required|exists:hospitals,id',
            'simulated_units' => 'nullable|integer|min:1',
            'blood_type_id' => 'nullable|exists:blood_types,id',
        ];
    }
}