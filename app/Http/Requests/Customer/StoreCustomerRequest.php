<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:customers,email',
            'contact_no' => 'required|numeric|digits_between:9,15|unique:customers,contact_no',
            'whatsapp_no' => 'nullable|numeric|digits_between:9,15|unique:customers,whatsapp_no',
            'profile_image' => 'nullable|string',
            'address' => 'nullable|string',
            'country_id' => 'nullable|numeric',
            'state_id' => 'nullable|numeric',
            'city_id' => 'nullable|numeric',
            'zipcode' => 'nullable|numeric',
            'otp' => 'nullable|numeric',
            'otp_status' => 'nullable|numeric',
            'is_active' => 'nullable|numeric'
        ];
    }
}
