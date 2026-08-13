<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HospitalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_number' => 'required|string|max:100',
            'address'        => 'required|string|max:500',
            'latitude'       => 'required|numeric|between:-90,90',
            'longitude'      => 'required|numeric|between:-180,180',
            'is_verified'    => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'license_number.required' => 'رقم الترخيص الخاص بالمستشفى مطلوب.',
            'address.required'        => 'عنوان المستشفى مطلوب.',
            'latitude.required'       => 'إحداثيات خط العرض مطلوبة.',
            'latitude.between'        => 'قيمة خط العرض غير صالحة.',
            'longitude.required'      => 'إحداثيات خط الطول مطلوبة.',
            'longitude.between'       => 'قيمة خط الطول غير صالحة.',
        ];
    }
}
