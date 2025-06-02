<?php

namespace App\Http\Requests\Dealer;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateVehicleRequest",
 *     type="object",
 *     title="Update Vehicle Request",
 *     description="Request body for updating a vehicle",
 *     required={"vehicle_type", "cmp_id", "model_id"},
 *     @OA\Property(property="vehicle_type", type="string", example="car"),
 *     @OA\Property(property="cmp_id", type="integer", example=1),
 *     @OA\Property(property="model_id", type="integer", example=10),
 *     @OA\Property(property="variant_id", type="integer", example=3),
 *     @OA\Property(property="fuel_type", type="integer", example=2),
 *     @OA\Property(property="mileage", type="number", format="float", example=18.5),
 *     @OA\Property(property="kms_driven", type="integer", example=24000),
 *     @OA\Property(property="manufacture_year", type="integer", example=2021),
 *     @OA\Property(property="registration_year", type="integer", example=2022),
 *     @OA\Property(property="color_id", type="integer", example=4),
 *     @OA\Property(property="regular_price", type="number", format="float", example=950000),
 *     @OA\Property(property="selling_price", type="number", format="float", example=920000),
 *     @OA\Property(property="is_active", type="boolean", example=true)
 * )
 */
class UpdateVehicleRequest extends FormRequest {
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'vehicle_type' => 'required|string',
            'cmp_id' => 'required|integer',
            'model_id' => 'required|integer',
            'variant_id' => 'nullable|integer',
            'fuel_type' => 'nullable|integer',
            'mileage' => 'nullable|numeric',
            'kms_driven' => 'nullable|integer',
            'manufacture_year' => 'nullable|integer',
            'registration_year' => 'nullable|integer',
            'color_id' => 'nullable|integer',
            'regular_price' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
        ];
    }
}
