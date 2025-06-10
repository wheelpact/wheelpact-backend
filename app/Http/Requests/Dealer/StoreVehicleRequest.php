<?php

namespace App\Http\Requests\Dealer;


use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        $rules = $this->sharedRules();

        if ($this->vehicle_type == 1) {
            $rules = array_merge($rules, $this->carRules());
        } elseif ($this->vehicle_type == 2) {
            $rules = array_merge($rules, $this->bikeRules());
        }

        return $rules;
    }

    private function sharedRules(): array {
        return [
            'vehicle_type' => 'required|in:1,2',
            'branch_id' => 'required|integer',
            'cmp_id' => 'required|integer',
            'model_id' => 'required|integer',
            'variant_id' => 'required|integer',
            'fuel_type' => 'required|integer',
            'mileage' => 'required|numeric',
            'kms_driven' => 'required|integer',
            'owner' => 'required|integer',
            'color_id' => 'nullable|integer',
            'featured_status' => 'required|in:1,2',
            'onsale_status' => 'required|in:1,2',
            'manufacture_year' => 'required|digits:4',
            'registration_year' => 'required|digits:4',
            'registered_state_id' => 'required|integer',
            'rto' => 'required|string|max:10',
            'insurance_type' => 'required|in:1,2',
            'accidental_status' => 'required|in:1,2',
            'flooded_status' => 'required|in:1,2',
            'last_service_kms' => 'required|integer',
            'is_active' => 'required|in:1,2,3,4',
            'created_by' => 'nullable|integer',
            'thumbnail_url' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    private function carRules(): array {
        return [
            'transmission_id' => 'required|integer',
            'body_type' => 'required|integer',
            'car_no_of_airbags' => 'required|integer',

            // Optional car features
            'car_central_locking' => 'nullable|integer|in:1,2,3',
            'car_seat_upholstery' => 'nullable|integer|in:1,2',
            'car_sunroof' => 'nullable|integer|in:1,2',
            'car_integrated_music_system' => 'nullable|integer|in:1,2',
            'car_rear_ac' => 'nullable|integer|in:1,2',
            'car_outside_rear_view_mirrors' => 'nullable|integer|in:1,2',
            'car_power_windows' => 'nullable|integer|in:1,2',
            'car_engine_start_stop' => 'nullable|integer|in:1,2',
            'car_headlamps' => 'nullable|integer|in:1,2',
            'car_power_steering' => 'nullable|integer|in:1,2',
        ];
    }

    private function bikeRules(): array {
        return [
            // Bike features
            'bike_headlight_type' => 'nullable|integer|in:1,2',
            'bike_odometer' => 'nullable|integer|in:1,2',
            'bike_drl' => 'nullable|integer|in:1,2',
            'bike_mobile_connectivity' => 'nullable|integer|in:1,2',
            'bike_gps_navigation' => 'nullable|integer|in:1,2',
            'bike_usb_charging_port' => 'nullable|integer|in:1,2',
            'bike_low_battery_indicator' => 'nullable|integer|in:1,2',
            'bike_under_seat_storage' => 'nullable|integer|in:1,2',
            'bike_speedometer' => 'nullable|integer|in:1,2',
            'bike_stand_alarm' => 'nullable|integer|in:1,2',
            'bike_low_fuel_indicator' => 'nullable|integer|in:1,2',
            'bike_low_oil_indicator' => 'nullable|integer|in:1,2',
            'bike_start_type' => 'nullable|integer|in:1,2,3',
            'bike_kill_switch' => 'nullable|integer|in:1,2',
            'bike_break_light' => 'nullable|integer|in:1,2',
            'bike_turn_signal_indicator' => 'nullable|integer|in:1,2',
        ];
    }
}
