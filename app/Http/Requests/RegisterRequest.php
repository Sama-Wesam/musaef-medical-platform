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
            // البيانات الأساسية
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:donor,hospital',

            /*
            |--------------------------------------------------------------------------
            | بيانات المتبرع
            |--------------------------------------------------------------------------
            */
            'blood_type_id' => 'required_if:role,donor|nullable|exists:blood_types,id',
            'birth_date'    => 'required_if:role,donor|nullable|date|before:-18 years',
            'gender'        => 'required_if:role,donor|nullable|in:male,female',

            /*
            |--------------------------------------------------------------------------
            | بيانات المستشفى / بنك الدم
            |--------------------------------------------------------------------------
            */
            'facility_name'  => 'required_if:role,hospital|nullable|string|max:255',
            'facility_type'  => 'required_if:role,hospital|nullable|in:hospital,blood_bank',
            'license_number' => 'required_if:role,hospital|nullable|string|max:100|unique:hospitals,license_number',
            'manager_name'   => 'required_if:role,hospital|nullable|string|max:255',
            'license_file'   => 'required_if:role,hospital|nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'اسم المستخدم مطلوب.',
            'email.required'             => 'البريد الإلكتروني مطلوب.',
            'email.unique'               => 'البريد الإلكتروني مستخدم مسبقاً.',
            'phone.required'             => 'رقم الهاتف مطلوب.',
            'password.required'          => 'كلمة المرور مطلوبة.',
            'password.min'               => 'كلمة المرور يجب ألا تقل عن 8 أحرف.',
            'password.confirmed'         => 'كلمتا المرور غير متطابقتين.',
            'role.required'              => 'يرجى تحديد نوع الحساب.',
            'birth_date.before'          => 'يجب أن يكون عمر المتبرع 18 عاماً على الأقل.',
            'blood_type_id.required_if'  => 'يرجى اختيار فصيلة الدم.',
            'gender.required_if'         => 'يرجى اختيار الجنس.',
            'facility_name.required_if'  => 'يرجى إدخال اسم الجهة الطبية.',
            'facility_type.required_if'  => 'يرجى اختيار نوع الجهة.',
            'license_number.required_if' => 'رقم الترخيص مطلوب.',
            'license_number.unique'      => 'رقم الترخيص هذا مستخدم مسبقاً.',
            'manager_name.required_if'   => 'يرجى إدخال اسم المسؤول.',
            'license_file.required_if'   => 'يرجى رفع نسخة من الترخيص.',
            'license_file.mimes'         => 'يجب أن يكون الملف بصيغة PDF أو JPG أو PNG.',
            'license_file.max'           => 'الحد الأقصى لحجم الملف هو 5MB.',
        ];
    }
}
