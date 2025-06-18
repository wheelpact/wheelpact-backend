<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;

class BranchUpdateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'dealer_id' => ['sometimes', 'integer', 'exists:users,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'branch_type' => ['sometimes', 'in:1,2'],
            'branch_supported_vehicle_type' => ['sometimes', 'in:1,2,3'],
            'branch_services' => ['sometimes', 'string'],
            'contact_number' => ['sometimes', 'string', 'max:15'],
            'whatsapp_no' => ['sometimes', 'string', 'max:25'],
            'email' => ['sometimes', 'email'],
            'country_id' => ['sometimes', 'integer', 'exists:countries,id'],
            'state_id' => ['sometimes', 'integer', 'exists:states,id'],
            'city_id' => ['sometimes', 'integer', 'exists:cities,id'],
            'address' => ['sometimes', 'string'],
            'short_description' => ['sometimes', 'string'],
            'map_latitude' => ['sometimes', 'string'],
            'map_longitude' => ['sometimes', 'string'],
            'map_city' => ['sometimes', 'string'],
            'map_district' => ['sometimes', 'string'],
            'map_state' => ['sometimes', 'string'],
            'is_active' => ['sometimes', 'in:1,2,3'],
            'is_admin_approved' => ['sometimes', 'in:0,1'],
            'branch_logo' => ['sometimes', 'string'], // or image file
            'branch_banner1' => ['nullable', 'string'],
            'branch_banner2' => ['nullable', 'string'],
            'branch_banner3' => ['nullable', 'string'],
            'branch_thumbnail' => ['nullable', 'string'],
        ];
    }
}
