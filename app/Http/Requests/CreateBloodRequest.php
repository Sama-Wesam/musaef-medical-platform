<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\EmergencyLevel;

class CreateBloodRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم مصرحاً له بإجراء هذا الطلب.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * قواعد التحقق (Validation Rules) التي تطبق على الطلب.
     */
    public function rules(): array
    {
        return [
            'blood_type_id'   => 'required|exists:blood_types,id',
            'units_required'  => 'required|integer|min:1|max:50',
            'emergency_level' => ['required', Rule::enum(EmergencyLevel::class)],
            'description'     => 'nullable|string|max:1000',
        ];
    }

    /**
     * رسائل الخطأ المخصصة.
     */
    public function messages(): array
    {
        return [
            'blood_type_id.required'   => 'يجب تحديد فصيلة الدم المطلوبة.',
            'blood_type_id.exists'     => 'فصيلة الدم المحددة غير موجودة في النظام.',
            'units_required.required'  => 'يجب تحديد عدد الوحدات المطلوبة.',
            'units_required.integer'   => 'عدد الوحدات يجب أن يكون رقماً صحيحاً.',
            'units_required.min'       => 'يجب طلب وحدة واحدة على الأقل.',
            'units_required.max'       => 'لا يمكن طلب أكثر من 50 وحدة في نفس الطلب.',
            'emergency_level.required' => 'يرجى تحديد مستوى الطوارئ للحالة.',
            'emergency_level.Illuminate\Validation\Rules\Enum' => 'مستوى الطوارئ المحدد غير صالح.',
            'description.max'          => 'وصف الحالة يجب ألا يتجاوز 1000 حرف.',
        ];
    }
}
