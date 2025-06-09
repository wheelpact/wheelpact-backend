<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('vehicleimages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');

            // Exterior main images
            $table->text('exterior_main_front_img')->nullable();
            $table->text('exterior_main_right_img')->nullable();
            $table->text('exterior_main_back_img')->nullable();
            $table->text('exterior_main_left_img')->nullable();
            $table->text('exterior_main_tank_img')->nullable();
            $table->text('exterior_main_handlebar_img')->nullable();
            $table->text('exterior_main_headlight_img')->nullable();
            $table->text('exterior_main_tail_light_img')->nullable();
            $table->text('exterior_main_speedometer_img')->nullable();
            $table->text('exterior_main_exhaust_img')->nullable();
            $table->text('exterior_main_seat_img')->nullable();
            $table->text('exterior_main_roof_img')->nullable();
            $table->text('exterior_main_bonetopen_img')->nullable();
            $table->text('exterior_main_engine_img')->nullable();

            // Diagonal images
            $table->text('exterior_diagnoal_right_front_img')->nullable();
            $table->text('exterior_diagnoal_right_back_img')->nullable();
            $table->text('exterior_diagnoal_left_back_img')->nullable();
            $table->text('exterior_diagnoal_left_front_img')->nullable();

            // Wheel images
            $table->text('exterior_wheel_front_img')->nullable();
            $table->text('exterior_wheel_rear_img')->nullable();
            $table->text('exterior_wheel_right_front_img')->nullable();
            $table->text('exterior_wheel_right_back_img')->nullable();
            $table->text('exterior_wheel_left_back_img')->nullable();
            $table->text('exterior_wheel_left_front_img')->nullable();
            $table->text('exterior_wheel_spare_img')->nullable();

            // Tyre thread images
            $table->text('exterior_tyrethread_front_img')->nullable();
            $table->text('exterior_tyrethread_back_img')->nullable();
            $table->text('exterior_tyrethread_right_front_img')->nullable();
            $table->text('exterior_tyrethread_right_back_img')->nullable();
            $table->text('exterior_tyrethread_left_back_img')->nullable();
            $table->text('exterior_tyrethread_left_front_img')->nullable();

            // Underbody images
            $table->text('exterior_underbody_front_img')->nullable();
            $table->text('exterior_underbody_rear_img')->nullable();
            $table->text('exterior_underbody_right_img')->nullable();
            $table->text('exterior_underbody_left_img')->nullable();

            // Interior images
            $table->text('interior_dashboard_img')->nullable();
            $table->text('interior_infotainment_system_img')->nullable();
            $table->text('interior_steering_wheel_img')->nullable();
            $table->text('interior_odometer_img')->nullable();
            $table->text('interior_gear_lever_img')->nullable();
            $table->text('interior_pedals_img')->nullable();
            $table->text('interior_front_cabin_img')->nullable();
            $table->text('interior_mid_cabin_img')->nullable();
            $table->text('interior_rear_cabin_img')->nullable();
            $table->text('interior_driver_side_door_panel_img')->nullable();
            $table->text('interior_driver_side_adjustment_img')->nullable();
            $table->text('interior_boot_inside_img')->nullable();
            $table->text('interior_boot_door_open_img')->nullable();

            // Other
            $table->text('others_keys_img')->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('vehicleimages');
    }
};