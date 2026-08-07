<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBloodRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم مصرحاً له بإجراء هذا الطلب.
     * نرجع true لأننا نتحقق من الصلاحيات عبر الـ Middleware مسبقاً.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * قواعد التحقق (Validation Rules) التي تطبق على الطلب.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'blood_type_id' => 'required|exists:blood_types,id',
            'units_required' => 'required|integer|min:1|max:50', // حد أقصى 50 وحدة في الطلب الواحد لمنع الأخطاء أو التلاعب
            'emergency_level' => 'required|in:normal,high,critical',
            'description' => 'nullable|string|max:1000', // وصف اختياري للحالة
        ];
    }

    /**
     * رسائل الخطأ المخصصة (اختياري لتحسين تجربة المستخدم).
     */
    public function messages(): array
    {
        return [
            'blood_type_id.required' => 'يجب تحديد فصيلة الدم المطلوبة.',
            'blood_type_id.exists' => 'فصيلة الدم المحددة غير موجودة في النظام.',
            'units_required.required' => 'يجب تحديد عدد الوحدات المطلوبة.',
            'units_required.max' => 'لا يمكن طلب أكثر من 50 وحدة في نفس الطلب.',
            'emergency_level.required' => 'يرجى تحديد مستوى الطوارئ للحالة.',
        ];
    }
}