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
            'donor_id' => 'required|exists:donors,id',
            'blood_request_id' => 'nullable|exists:blood_requests,id',
            'units_donated' => 'required|integer|min:1|max:3', // عادة المتبرع يتبرع بوحدة واحدة أو اثنتين
            'donation_date' => 'required|date|before_or_equal:today',
        ];
    }
}