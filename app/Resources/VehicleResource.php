<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Vehicle",
 *     title="Vehicle Resource",
 *     description="Vehicle resource with dealer and vehicle details",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="branch_id", type="integer", example=10),
 *     @OA\Property(property="branch_name", type="string", example="Mumbai Branch"),
 *     @OA\Property(property="branch_contact_number", type="string", example="+91-9876543210"),
 *     @OA\Property(property="branch_country_id", type="integer", example=1),
 *     @OA\Property(property="branch_country_name", type="string", example="India"),
 *     @OA\Property(property="branch_state_id", type="integer", example=21),
 *     @OA\Property(property="branch_state_name", type="string", example="Maharashtra"),
 *     @OA\Property(property="branch_city_id", type="integer", example=203),
 *     @OA\Property(property="branch_city_name", type="string", example="Mumbai"),
 *     @OA\Property(property="vehicle_type_name", type="string", example="Car"),
 *     @OA\Property(property="cmp_id", type="integer", example=5),
 *     @OA\Property(property="cmp_name", type="string", example="Hyundai"),
 *     @OA\Property(property="model_id", type="integer", example=9),
 *     @OA\Property(property="model_name", type="string", example="Creta"),
 *     @OA\Property(property="variant_id", type="integer", example=3),
 *     @OA\Property(property="variant_name", type="string", example="SX (O) Turbo"),
 *     @OA\Property(property="fuel_type_id", type="integer", example=1),
 *     @OA\Property(property="fuel_type", type="string", example="Petrol"),
 *     @OA\Property(property="body_type_id", type="integer", example=2),
 *     @OA\Property(property="body_type", type="string", example="SUV"),
 * )
 */
class VehicleResource extends JsonResource {

    public function toArray($request) {

        if ($request->routeIs('dealer.vehicles.index')) {
            // Light/summary response for list endpoint
            return [

                'vehicle_id' => $this->id,
                'unique_id' => $this->unique_id,

                'branch_id' => $this->branch_id,
                'branch_name' => $this->branches->name ?? null,
                // 'branch_state_id' => $this->branches->state_id ?? null,
                // 'branch_state_name' => $this->branches->state?->name ?? null,
                // 'branch_city_id' => $this->branches->city_id ?? null,
                // 'branch_city_name' => $this->branches->city?->name ?? null,
                // 'branch_country_id' => $this->branches->country_id ?? null,
                // 'branch_country_name' => $this->branches->country?->name ?? null,

                'manufacture_year' => $this->manufacture_year,
                'registration_year' => $this->registration_year,
                'registered_state_id' => $this->registered_state_id,
                'registered_state_name' =>  $this->state->name ?? null,

                'vehicle_type_id' => $this->vehicle_type,
                'vehicle_type_name' => $this->vehicleType?->name,

                'vehicle_company_id' => $this->cmp_id,
                'vehicle_company_name' => $this->vehicleCompany->cmp_name ?? null,

                'kms_driven' => $this->kms_driven,
                'owner' => $this->owner,
                'transmission_id' => $this->transmission_id,
                'transmission_name' => $this->transmission?->title,

                'model_id' => $this->model_id,
                'model_name' => $this->model->model_name ?? null,

                'variant_id' => $this->variant_id,
                'variant_name' => $this->variant->name ?? null,

                'fuel_type_id' => $this->fuel_type,
                'fuel_type_name' => $this->fuelType?->name,

                'body_type_id' => $this->body_type,
                'body_type_name' => $this->bodyType?->title,

                'indian_rto_id' => $this->rto,
                'indian_rto_state_code' =>  $this->indiarto->rto_state_code ?? null,
                'indian_rto_place' =>  $this->indiarto->place ?? null
            ];
        }

        if ($request->routeIs('dealer.vehicles.store')) {
            return [
                'id' => $this->id,
                'unique_id' => $this->unique_id,
                'vehicle_type' => $this->vehicle_type,
                'branch_id' => $this->branch_id,
                'cmp_id' => $this->cmp_id,
                'model_id' => $this->model_id,
                'variant_id' => $this->variant_id,
                'fuel_type' => $this->fuel_type,
                'body_type' => $this->body_type,
                'mileage' => $this->mileage,
                'kms_driven' => $this->kms_driven,
                'owner' => $this->owner,
                'transmission_id' => $this->transmission_id,
                'color_id' => $this->color_id,
                'featured_status' => $this->featured_status,
                'search_keywords' => $this->search_keywords,
                'onsale_status' => $this->onsale_status,
                'onsale_percentage' => $this->onsale_percentage,
                'manufacture_year' => $this->manufacture_year,
                'registration_year' => $this->registration_year,
                'registered_state_id' => $this->registered_state_id,
                'rto' => $this->rto,
                'insurance_type' => $this->insurance_type,
                'insurance_validity' => $this->insurance_validity,
                'accidental_status' => $this->accidental_status,
                'flooded_status' => $this->flooded_status,
                'last_service_kms' => $this->last_service_kms,
                'last_service_date' => $this->last_service_date,
                'pricing' => [
                    'regular_price' => $this->regular_price,
                    'selling_price' => $this->selling_price,
                    'pricing_type' => $this->pricing_type,
                    'emi_option' => $this->emi_option,
                    'avg_interest_rate' => $this->avg_interest_rate,
                    'tenure_months' => $this->tenure_months,
                    'reservation_amt' => $this->reservation_amt,
                ],
                'thumbnail_url' => asset('storage/' . $this->thumbnail_path),

                // Conditional Blocks
                'car_features' => $this->vehicle_type == 1 ? [
                    'no_of_airbags' => $this->car_no_of_airbags,
                    'central_locking' => $this->car_central_locking,
                    'seat_upholstery' => $this->car_seat_upholstery,
                    'sunroof' => $this->car_sunroof,
                    'integrated_music_system' => $this->car_integrated_music_system,
                    'rear_ac' => $this->car_rear_ac,
                    'orvm' => $this->car_outside_rear_view_mirrors,
                    'power_windows' => $this->car_power_windows,
                    'engine_start_stop' => $this->car_engine_start_stop,
                    'headlamps' => $this->car_headlamps,
                    'power_steering' => $this->car_power_steering,
                ] : null,

                'bike_features' => $this->vehicle_type == 2 ? [
                    'headlight_type' => $this->bike_headlight_type,
                    'odometer' => $this->bike_odometer,
                    'drl' => $this->bike_drl,
                    'mobile_connectivity' => $this->bike_mobile_connectivity,
                    'gps_navigation' => $this->bike_gps_navigation,
                    'usb_charging_port' => $this->bike_usb_charging_port,
                    'low_battery_indicator' => $this->bike_low_battery_indicator,
                    'under_seat_storage' => $this->bike_under_seat_storage,
                    'speedometer' => $this->bike_speedometer,
                    'stand_alarm' => $this->bike_stand_alarm,
                    'low_fuel_indicator' => $this->bike_low_fuel_indicator,
                    'low_oil_indicator' => $this->bike_low_oil_indicator,
                    'start_type' => $this->bike_start_type,
                    'kill_switch' => $this->bike_kill_switch,
                    'break_light' => $this->bike_break_light,
                    'turn_signal_indicator' => $this->bike_turn_signal_indicator,
                ] : null,

                'status' => [
                    'is_active' => $this->is_active,
                    'is_admin_approved' => $this->is_admin_approved,
                    'sold_reason' => $this->soldReason,
                ],
                'created_by' => $this->created_by,
                'updated_by' => $this->updated_by,
                'admin_approval_dt' => $this->admin_approval_dt,
                'created_at' => $this->created_at,
            ];
        }

        

        // Fallback
        return parent::toArray($request);

        //     return [
        //         'id' => $this->id,
        //         'unique_id' => $this->unique_id,
        //         'branch_id' => $this->branch_id,
        //         'branch_name' => $this->branches->name ?? null,
        //         'vehicle_type' => $this->vehicle_type,
        //         'cmp_id' => $this->cmp_id,
        //         'model_id' => $this->model_id,
        //         'fuel_type' => $this->fuel_type,
        //         'body_type' => $this->body_type,
        //         'variant_id' => $this->variant_id,
        //         'mileage' => $this->mileage,
        //         'kms_driven' => $this->kms_driven,
        //         'owner' => $this->owner,
        //         'transmission_id' => $this->transmission_id,
        //         'color_id' => $this->color_id,
        //         'featured_status' => $this->featured_status,
        //         'search_keywords' => $this->search_keywords,
        //         'onsale_status' => $this->onsale_status,
        //         'onsale_percentage' => $this->onsale_percentage,
        //         'manufacture_year' => $this->manufacture_year,
        //         'registration_year' => $this->registration_year,
        //         'registered_state_id' => $this->registered_state_id,
        //         'rto' => $this->rto,
        //         'insurance_type' => $this->insurance_type,
        //         'insurance_validity' => $this->insurance_validity,
        //         'accidental_status' => $this->accidental_status,
        //         'flooded_status' => $this->flooded_status,
        //         'last_service_kms' => $this->last_service_kms,
        //         'last_service_date' => $this->last_service_date,
        //         'car_no_of_airbags' => $this->car_no_of_airbags,
        //         'car_central_locking' => $this->car_central_locking,
        //         'car_seat_upholstery' => $this->car_seat_upholstery,
        //         'car_sunroof' => $this->car_sunroof,
        //         'car_integrated_music_system' => $this->car_integrated_music_system,
        //         'car_rear_ac' => $this->car_rear_ac,
        //         'car_outside_rear_view_mirrors' => $this->car_outside_rear_view_mirrors,
        //         'car_power_windows' => $this->car_power_windows,
        //         'car_engine_start_stop' => $this->car_engine_start_stop,
        //         'car_headlamps' => $this->car_headlamps,
        //         'car_power_steering' => $this->car_power_steering,
        //         'bike_headlight_type' => $this->bike_headlight_type,
        //         'bike_odometer' => $this->bike_odometer,
        //         'bike_drl' => $this->bike_drl,
        //         'bike_mobile_connectivity' => $this->bike_mobile_connectivity,
        //         'bike_gps_navigation' => $this->bike_gps_navigation,
        //         'bike_usb_charging_port' => $this->bike_usb_charging_port,
        //         'bike_low_battery_indicator' => $this->bike_low_battery_indicator,
        //         'bike_under_seat_storage' => $this->bike_under_seat_storage,
        //         'bike_speedometer' => $this->bike_speedometer,
        //         'bike_stand_alarm' => $this->bike_stand_alarm,
        //         'bike_low_fuel_indicator' => $this->bike_low_fuel_indicator,
        //         'bike_low_oil_indicator' => $this->bike_low_oil_indicator,
        //         'bike_start_type' => $this->bike_start_type,
        //         'bike_kill_switch' => $this->bike_kill_switch,
        //         'bike_break_light' => $this->bike_break_light,
        //         'bike_turn_signal_indicator' => $this->bike_turn_signal_indicator,
        //         'regular_price' => $this->regular_price,
        //         'selling_price' => $this->selling_price,
        //         'pricing_type' => $this->pricing_type,
        //         'emi_option' => $this->emi_option,
        //         'avg_interest_rate' => $this->avg_interest_rate,
        //         'tenure_months' => $this->tenure_months,
        //         'reservation_amt' => $this->reservation_amt,
        //         'thumbnail_url' => $this->thumbnail_url,
        //         'is_active' => $this->is_active,
        //         'is_admin_approved' => $this->is_admin_approved,
        //         'soldReason' => $this->soldReason,
        //         'created_by' => $this->created_by,
        //         'created_datetime' => $this->created_datetime,
        //         'updated_by' => $this->updated_by,
        //         'admin_approval_dt' => $this->admin_approval_dt,
        //         'rtoName' => $this->rto_state_code,
        //         'created_at' => $this->created_at,
        //         'updated_at' => $this->updated_at,
        //         'deleted_at' => $this->deleted_at,
        //     ];
    }
}
