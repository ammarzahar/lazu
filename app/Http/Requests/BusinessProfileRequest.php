<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'in:service,ecom,local'],
            'product_or_service' => ['required', 'string'],
            'price_min' => ['required', 'numeric', 'min:0'],
            'price_max' => ['required', 'numeric', 'min:0'],
            'gross_margin_pct' => ['nullable', 'integer', 'min:0', 'max:100'],
            'target_audience' => ['required', 'string'],
            'main_channel' => ['required', 'in:meta_ads,whatsapp,landing'],
            'monthly_objective' => ['required', 'in:leads,sales,awareness'],
        ];
    }
}
