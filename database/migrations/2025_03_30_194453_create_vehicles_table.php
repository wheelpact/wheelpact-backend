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
            $table->text('unique_id')->unique();
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
            $table->unsignedBigInteger('color_id')->nullable()->comment('foriegn key of color table');
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

            // Car specific fields
            $table->unsignedTinyInteger('car_no_of_airbags')->nullable()->comment('1=none, 2=2 airbags, 3=4 airbags, 4=6 airbags');
            $table->unsignedTinyInteger('car_central_locking')->nullable()->comment('1=none, 2=key, 3=keyless');
            $table->unsignedTinyInteger('car_seat_upholstery')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_sunroof')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_integrated_music_system')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_rear_ac')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_outside_rear_view_mirrors')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_power_windows')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_engine_start_stop')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('car_headlamps')->nullable()->comment('1=LED, 2=Hologen');
            $table->unsignedTinyInteger('car_power_steering')->nullable()->comment('1=yes, 2=no');

            // Bike specific fields
            $table->unsignedTinyInteger('bike_headlight_type')->nullable()->comment('1=LED, 2=Hologen');
            $table->unsignedTinyInteger('bike_odometer')->nullable()->comment('1=Digital, 2=Analogue');
            $table->unsignedTinyInteger('bike_drl')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_mobile_connectivity')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_gps_navigation')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_usb_charging_port')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_low_battery_indicator')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_under_seat_storage')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_speedometer')->nullable()->comment('1=Digital, 2=Analogue');
            $table->unsignedTinyInteger('bike_stand_alarm')->nullable()->comment('1=Digital, 2=Analogue');
            $table->unsignedTinyInteger('bike_low_fuel_indicator')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_low_oil_indicator')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_start_type')->nullable()->comment('1=Electric, 2=Kick start, 3=Electric + Kick Start');
            $table->unsignedTinyInteger('bike_kill_switch')->nullable()->comment('1=yes, 2=no');
            $table->unsignedTinyInteger('bike_break_light')->nullable()->comment('1=Hologen, 2=Analogue');
            $table->unsignedTinyInteger('bike_turn_signal_indicator')->nullable()->comment('1=Hologen Bulb, 2=LED');
            $table->float('regular_price')->nullable();
            $table->float('selling_price')->nullable();
            $table->unsignedTinyInteger('pricing_type')->nullable()->comment('1=Fixed, 2=Negotiable');
            $table->unsignedTinyInteger('emi_option')->nullable()->comment('1=yes, 2=no');
            $table->float('avg_interest_rate')->nullable();
            $table->integer('tenure_months')->default(0);
            $table->integer('reservation_amt')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->unsignedTinyInteger('is_active')->comment('1=Active, 2=Inactive, 3=Deleted, 4=Sold');
            $table->enum('is_admin_approved', ['0', '1'])->default('0');
            $table->dateTime('admin_approval_dt')->nullable();
            $table->text('soldReason')->nullable()->comment('Reason for vehicle sold');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('created_datetime')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            //$table->dateTime('updated_datetime');
            $table->timestamps();  // Adds `created_at` and `updated_at` columns
            $table->softDeletes(); // Adds a nullable `deleted_at` column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('vehicles');
    }
};
