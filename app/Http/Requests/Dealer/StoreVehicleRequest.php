<?php

namespace App\Http\Requests\Dealer;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreVehicleRequest",
 *     type="object",
 *     required={"branch_id", "vehicle_type", "cmp_id"},
 *     @OA\Property(property="branch_id", type="integer", example=1),
 *     @OA\Property(property="vehicle_type", type="integer", example=2),
 *     @OA\Property(property="cmp_id", type="integer", example=4),
 *     @OA\Property(property="model_id", type="integer", example=7),
 *     @OA\Property(property="fuel_type", type="integer", example=1),
 *     @OA\Property(property="thumbnail_url", type="string", format="binary")
 * )
 */
class StoreVehicleRequest extends FormRequest {

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
            'unique_id'                       => 'nullable|string|max:255',
            'branch_id'                       => 'required|integer|exists:branches,id',
            'vehicle_type'                    => 'required|integer',
            'cmp_id'                          => 'required|integer|exists:companies,id',
            'model_id'                        => 'required|integer|exists:models,id',
            'fuel_type'                       => 'required|integer|exists:fuel_types,id',
            'body_type'                       => 'required|integer|exists:body_types,id',
            'variant_id'                      => 'required|integer|exists:variants,id',
            'mileage'                         => 'required|numeric|min:0',
            'kms_driven'                      => 'required|numeric|min:0',
            'owner'                           => 'required|integer|min:1',
            'transmission_id'                 => 'required|integer|exists:transmissions,id',
            'color_id'                        => 'required|integer|exists:colors,id',
            'featured_status'                 => 'nullable|boolean',
            'search_keywords'                 => 'nullable|string|max:255',
            'onsale_status'                   => 'nullable|boolean',
            'onsale_percentage'               => 'nullable|numeric|min:0|max:100',
            'manufacture_year'                => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'registration_year'               => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'registered_state_id'             => 'nullable|integer|exists:states,id',
            'rto'                             => 'nullable|string|max:50',
            'insurance_type'                  => 'nullable|string|in:comprehensive,third-party,none',
            'insurance_validity'              => 'nullable|date',
            'accidental_status'               => 'nullable|boolean',
            'flooded_status'                  => 'nullable|boolean',
            'last_service_kms'                => 'nullable|numeric|min:0',
            'last_service_date'               => 'nullable|date',
            'car_no_of_airbags'               => 'nullable|integer|min:0',
            'car_central_locking'             => 'nullable|boolean',
            'car_seat_upholstery'             => 'nullable|string|max:50',
            'car_sunroof'                     => 'nullable|boolean',
            'car_integrated_music_system'     => 'nullable|boolean',
            'car_rear_ac'                     => 'nullable|boolean',
            'car_outside_rear_view_mirrors'   => 'nullable|boolean',
            'car_power_windows'               => 'nullable|boolean',
            'car_engine_start_stop'           => 'nullable|boolean',
            'car_headlamps'                   => 'nullable|string|max:100',
            'car_power_steering'              => 'nullable|boolean',
            'bike_headlight_type'             => 'nullable|string|max:100',
            'bike_odometer'                   => 'nullable|numeric',
            'bike_drl'                        => 'nullable|boolean',
            'bike_mobile_connectivity'        => 'nullable|boolean',
            'bike_gps_navigation'             => 'nullable|boolean',
            'bike_usb_charging_port'          => 'nullable|boolean',
            'bike_low_battery_indicator'      => 'nullable|boolean',
            'bike_under_seat_storage'         => 'nullable|boolean',
            'bike_speedometer'                => 'nullable|string|max:50',
            'bike_stand_alarm'                => 'nullable|boolean',
            'bike_low_fuel_indicator'         => 'nullable|boolean',
            'bike_low_oil_indicator'          => 'nullable|boolean',
            'bike_start_type'                 => 'nullable|string|max:50',
            'bike_kill_switch'                => 'nullable|boolean',
            'bike_break_light'                => 'nullable|boolean',
            'bike_turn_signal_indicator'      => 'nullable|boolean',
            'regular_price'                   => 'required|numeric|min:0',
            'selling_price'                   => 'required|numeric|min:0',
            'pricing_type'                    => 'nullable|string|in:fixed,negotiable,emi',
            'emi_option'                      => 'nullable|boolean',
            'avg_interest_rate'               => 'nullable|numeric|min:0|max:100',
            'tenure_months'                   => 'nullable|integer|min:1',
            'reservation_amt'                 => 'nullable|numeric|min:0',
            'thumbnail_url'                   => 'nullable|string|max:255',
            'is_active'                       => 'nullable|boolean',
            'is_admin_approved'               => 'nullable|boolean',
            'soldReason'                      => 'nullable|string|max:255',
            'created_by'                      => 'nullable|integer|exists:users,id',
            'created_datetime'                => 'nullable|date',
            'updated_by'                      => 'nullable|integer|exists:users,id',
            'admin_approval_dt'               => 'nullable|date',
            // 'images'                          => 'nullable|array',
            // 'images.*'                        => 'string|max:255',

            // These are automatically handled, usually not passed in request
            'created_at'                     => 'nullable|date',
            'updated_at'                     => 'nullable|date',
            'deleted_at'                     => 'nullable|date',
        ];
    }
}
