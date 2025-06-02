<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleImages extends Model {

   protected $table = 'vehicleimages';

    protected $fillable = [
        'vehicle_id',
        'exterior_main_front_img',
        'exterior_main_right_img',
        'exterior_main_back_img',
        'exterior_main_left_img',
        'exterior_main_tank_img',
        'exterior_main_handlebar_img',
        'exterior_main_headlight_img',
        'exterior_main_tail_light_img',
        'exterior_main_speedometer_img',
        'exterior_main_exhaust_img',
        'exterior_main_seat_img',
        'exterior_main_roof_img',
        'exterior_main_bonetopen_img',
        'exterior_main_engine_img',
        'exterior_diagnoal_right_front_img',
        'exterior_diagnoal_right_back_img',
        'exterior_diagnoal_left_back_img',
        'exterior_diagnoal_left_front_img',
        'exterior_wheel_front_img',
        'exterior_wheel_rear_img',
        'exterior_wheel_right_front_img',
        'exterior_wheel_right_back_img',
        'exterior_wheel_left_back_img',
        'exterior_wheel_left_front_img',
        'exterior_wheel_spare_img',
        'exterior_tyrethread_front_img',
        'exterior_tyrethread_back_img',
        'exterior_tyrethread_right_front_img',
        'exterior_tyrethread_right_back_img',
        'exterior_tyrethread_left_back_img',
        'exterior_tyrethread_left_front_img',
        'exterior_underbody_front_img',
        'exterior_underbody_rear_img',
        'exterior_underbody_right_img',
        'exterior_underbody_left_img',
        'interior_dashboard_img',
        'interior_infotainment_system_img',
        'interior_steering_wheel_img',
        'interior_odometer_img',
        'interior_gear_lever_img',
        'interior_pedals_img',
        'interior_front_cabin_img',
        'interior_mid_cabin_img',
        'interior_rear_cabin_img',
        'interior_driver_side_door_panel_img',
        'interior_driver_side_adjustment_img',
        'interior_boot_inside_img',
        'interior_boot_door_open_img',
        'others_keys_img'
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicles::class);
    }
}
