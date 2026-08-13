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
            'hospital_id'     => 'required|exists:hospitals,id',
            'simulated_units' => 'nullable|integer|min:1|max:500',
            'blood_type_id'   => 'nullable|exists:blood_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'hospital_id.required'  => 'معرف المستشفى مطلوب لإجراء التحليل.',
            'hospital_id.exists'    => 'المستشفى المحدد غير موجود في قاعدة البيانات.',
            'simulated_units.integer' => 'عدد الوحدات المحاكاة يجب أن يكون رقماً صحيحاً.',
            'simulated_units.min'     => 'يجب أن تكون الوحدات المحاكاة وحدة واحدة على الأقل.',
            'simulated_units.max'     => 'الحد الأقصى للوحدات المحاكاة هو 500 وحدة.',
            'blood_type_id.exists'   => 'فصيلة الدم المحددة غير موجودة.',
        ];
    }
}
