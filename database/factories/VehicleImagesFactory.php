<?php

namespace Database\Factories;

use App\Models\VehicleImages;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleImagesFactory extends Factory {
    protected $model = VehicleImages::class;

    public function definition(): array {
        return [
            'vehicle_id' => \App\Models\Vehicles::factory(), // creates a vehicle if needed
            'exterior_main_front_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_right_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_back_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_left_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_tank_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_handlebar_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_headlight_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_tail_light_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_speedometer_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_exhaust_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_seat_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_roof_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_bonetopen_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_main_engine_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_diagnoal_right_front_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_diagnoal_right_back_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_diagnoal_left_back_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_diagnoal_left_front_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_wheel_front_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_wheel_rear_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_wheel_right_front_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_wheel_right_back_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_wheel_left_back_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_wheel_left_front_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_wheel_spare_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_tyrethread_front_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_tyrethread_back_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_tyrethread_right_front_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_tyrethread_right_back_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_tyrethread_left_back_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_tyrethread_left_front_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_underbody_front_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_underbody_rear_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_underbody_right_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'exterior_underbody_left_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_dashboard_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_infotainment_system_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_steering_wheel_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_odometer_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_gear_lever_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_pedals_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_front_cabin_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_mid_cabin_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_rear_cabin_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_driver_side_door_panel_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_driver_side_adjustment_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_boot_inside_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'interior_boot_door_open_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
            'others_keys_img' => 'vehicle_images/' . $this->faker->uuid . '.jpg',
        ];
    }
}
