<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:donor,hospital',
            
            // بيانات خاصة بالمتبرع فقط
            'blood_type_id' => 'required_if:role,donor|exists:blood_types,id',
            'birth_date' => 'required_if:role,donor|date|before:-18 years', // يجب أن يكون عمره 18+
            'gender' => 'required_if:role,donor|in:male,female',
            
            // بيانات خاصة بالمستشفى فقط
            'license_number' => 'required_if:role,hospital|string|unique:hospitals,license_number',
            'address' => 'required_if:role,hospital|string|max:500',
            'latitude' => 'required_if:role,hospital|numeric',
            'longitude' => 'required_if:role,hospital|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً.',
            'birth_date.before' => 'يجب أن يكون عمر المتبرع 18 عاماً على الأقل للمشاركة.',
            'license_number.required_if' => 'رقم ترخيص المستشفى مطلوب.',
        ];
    }
}