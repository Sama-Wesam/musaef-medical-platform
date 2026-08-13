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
        $userId = $this->user()?->id;

        return [
            'name'         => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:255|unique:users,email,' . $userId,
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:500',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'is_available' => 'nullable|boolean',
        ];
    }

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
            'latitude.between'  => 'قيمة خط العرض غير صالحة.',
            'longitude.numeric' => 'قيمة خط الطول يجب أن تكون رقمية.',
            'longitude.between' => 'قيمة خط الطول غير صالحة.',
        ];
    }
}
