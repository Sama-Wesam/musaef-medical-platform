<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // استخراج ID المستخدم الحالي لمنع خطأ البريد الإلكتروني المتكرر لنفس المستخدم
        $userId = $this->user()?->id;

        return [
            'name'         => 'nullable|string|max:255',
            'email'        => 'nullable|email|unique:users,email,' . $userId,
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:500',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'is_available' => 'nullable|boolean',
        ];
    }

    /**
     * رسائل الخطأ المخصصة لتحديث الملف الشخصي.
     */
    public function messages(): array
    {
        return [
            'name.string'       => 'اسم المستخدم يجب أن يكون نصاً.',
            'name.max'          => 'اسم المستخدم طويل جداً.',
            'email.email'       => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique'      => 'البريد الإلكتروني مُستخدم بالفعل من قبل حساب آخر.',
            'phone.max'         => 'رقم الهاتف يجب ألا يتجاوز 20 رقماً.',
            'address.max'       => 'العنوان طويل جداً.',
            'latitude.numeric'  => 'قيمة خط العرض يجب أن تكون رقمية.',
            'longitude.numeric' => 'قيمة خط الطول يجب أن تكون رقمية.',
        ];
    }
}
