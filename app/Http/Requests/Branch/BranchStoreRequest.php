<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;


class BranchStoreRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'branch_type' => 'required|integer|in:1,2',
            'branch_supported_vehicle_type' => 'required|integer|in:1,2,3',
            'branch_services' => 'required|string',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
            'address' => 'required|string',
            'contact_number' => 'required|string|regex:/^[0-9]{10,11}$/',
            'whatsapp_no' => 'required|string|max:25',
            'email' => 'required|email',
            'short_description' => 'required|string',
            'branch_map' => 'required|string',
            'map_latitude' => 'required|string',
            'map_longitude' => 'required|string',
            'map_city' => 'required|string',
            'map_district' => 'required|string',
            'map_state' => 'required|string',
            'branch_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'branch_logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'branch_banner1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'branch_banner2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'branch_banner3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deliverables' => 'nullable|array',
            'deliverables.*.img_name' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deliverables.*.type' => 'required_with:deliverables|string',
        ];
    }
}
