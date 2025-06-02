<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 20)->unique();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedTinyInteger('vehicle_type')->comment('1=car, 2=bike');
            $table->unsignedBigInteger('cmp_id')->nullable()->comment('vehicle make company id');
            $table->unsignedBigInteger('model_id')->nullable()->comment('vehicle model id');
            $table->unsignedBigInteger('fuel_type')->nullable()->comment('fuel type id');
            $table->unsignedBigInteger('body_type')->nullable()->comment('body type id');
            $table->unsignedBigInteger('variant_id')->nullable()->comment('variant id');
            $table->integer('mileage')->nullable()->comment('in kmpl');
            $table->integer('kms_driven')->nullable()->comment('in kms');
            $table->integer('owner')->nullable()->comment('1=First Owner, 2=Second Owner, 3=Third Owner, 4=Fourth Owner, 5=Fifth Owner');
            $table->unsignedBigInteger('transmission_id')->nullable()->comment('foriegn key of transmission table');
            $table->unsignedBigInteger('color_id');
            $table->unsignedTinyInteger('featured_status')->comment('1=Yes, 2=No');
            $table->text('search_keywords')->nullable();
            $table->unsignedTinyInteger('onsale_status')->comment('1=yes, 2=no');
            $table->float('onsale_percentage')->nullable();
            $table->year('manufacture_year');
            $table->year('registration_year');
            $table->unsignedBigInteger('registered_state_id');
            $table->string('rto', 10);
            $table->unsignedTinyInteger('insurance_type')->comment('1=Third-Party, 2=Comprehensive');
            $table->date('insurance_validity')->nullable();
            $table->unsignedTinyInteger('accidental_status')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('flooded_status')->comment('1=yes, 2=no');
            $table->integer('last_service_kms');
            $table->date('last_service_date')->nullable();
            $table->unsignedTinyInteger('car_no_of_airbags');
            $table->unsignedTinyInteger('car_central_locking')->comment('1=none, 2=key, 3=keyless');
            $table->unsignedTinyInteger('car_seat_upholstery')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_sunroof')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_integrated_music_system')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_rear_ac')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_outside_rear_view_mirrors')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_power_windows')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_engine_start_stop')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_headlamps')->comment('1=LED, 2=Hologen');
            $table->unsignedTinyInteger('car_power_steering')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_headlight_type')->comment('1=LED, 2=Hologen');
            $table->unsignedTinyInteger('bike_odometer')->comment('1=Digital, 2=Analogue');
            $table->unsignedTinyInteger('bike_drl')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_mobile_connectivity')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_gps_navigation')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_usb_charging_port')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_low_battery_indicator')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_under_seat_storage')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_speedometer')->comment('1=Digital, 2=Analogue');
            $table->unsignedTinyInteger('bike_stand_alarm')->comment('1=Digital, 2=Analogue');
            $table->unsignedTinyInteger('bike_low_fuel_indicator')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_low_oil_indicator')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_start_type')->comment('1=Electric, 2=Kick start, 3=Electric + Kick Start');
            $table->unsignedTinyInteger('bike_kill_switch')->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_break_light')->comment('1=Hologen, 2=Analogue');
            $table->unsignedTinyInteger('bike_turn_signal_indicator')->comment('1=Hologen Bulb, 2=LED');
            $table->float('regular_price')->nullable();
            $table->float('selling_price')->nullable();
            $table->unsignedTinyInteger('pricing_type')->comment('1=Fixed, 2=Negotiable');
            $table->unsignedTinyInteger('emi_option')->comment('1=yes, 2=no');
            $table->float('avg_interest_rate')->nullable();
            $table->integer('tenure_months')->default(0);
            $table->integer('reservation_amt');
            $table->text('thumbnail_url');
            $table->unsignedTinyInteger('is_active')->comment('1=Active, 2=Inactive, 3=Deleted, 4=Sold');
            $table->enum('is_admin_approved', ['0', '1'])->default('0');
            $table->text('soldReason');
            $table->unsignedBigInteger('created_by');
            $table->dateTime('created_datetime');
            $table->unsignedBigInteger('updated_by');
            $table->dateTime('updated_datetime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('vehicles');
    }
};
