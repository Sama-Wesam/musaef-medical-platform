<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'donor_id'         => 'required|exists:donors,id',
            'blood_request_id' => 'nullable|exists:blood_requests,id',
            'units_donated'    => 'required|integer|min:1|max:3',
            'donation_date'    => 'required|date|before_or_equal:today',
            'notes'            => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'donor_id.required'          => 'يرجى اختيار المتبرع.',
            'donor_id.exists'            => 'المتبرع المحدد غير موجود.',
            'blood_request_id.exists'    => 'طلب الدم المرتبط غير موجود.',
            'units_donated.required'     => 'عدد الوحدات المتبرع بها مطلوب.',
            'units_donated.min'          => 'يجب أن تكون وحدة واحدة على الأقل.',
            'units_donated.max'          => 'لا يمكن تسجيل أكثر من 3 وحدات في عملية التبرع الواحدة.',
            'donation_date.required'     => 'تاريخ التبرع مطلوب.',
            'donation_date.before_or_equal' => 'تاريخ التبرع لا يمكن أن يكون في المستقبل.',
            'notes.max'                  => 'الملاحظات يجب ألا تتجاوز 500 حرف.',
        ];
    }
}
