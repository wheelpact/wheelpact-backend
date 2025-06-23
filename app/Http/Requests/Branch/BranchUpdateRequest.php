<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;

class BranchUpdateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'sometimes|string|max:255',
            'branch_type' => 'sometimes|integer|in:1,2',
            'branch_supported_vehicle_type' => 'sometimes|integer|in:1,2,3',
            'branch_services' => 'sometimes|string',
            'country_id' => 'sometimes|integer',
            'state_id' => 'sometimes|integer',
            'city_id' => 'sometimes|integer',
            'address' => 'sometimes|string',
            'contact_number' => 'sometimes|string|regex:/^[0-9]{10,11}$/',
            'whatsapp_no' => 'sometimes|string|max:25',
            'email' => 'sometimes|email',
            'short_description' => 'sometimes|string',
            'branch_map' => 'nullable|string',
            'map_latitude' => 'nullable|string',
            'map_longitude' => 'nullable|string',
            'map_city' => 'nullable|string',
            'map_district' => 'nullable|string',
            'map_state' => 'nullable|string',
            'branch_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'branch_banner1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'branch_banner2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'branch_banner3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deliverables_img_name' => 'sometimes|array',
            'deliverables_img_name.*' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            
        ];
    }
}
