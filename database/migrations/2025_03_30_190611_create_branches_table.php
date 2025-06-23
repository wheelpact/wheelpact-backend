<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name', 100)->nullable();
            $table->string('branch_banner1')->nullable();
            $table->string('branch_banner2')->nullable();
            $table->string('branch_banner3')->nullable();
            $table->string('branch_thumbnail')->nullable();
            $table->string('branch_logo')->nullable()->comment('logo of the branch');
            $table->integer('branch_type')->nullable()->comment('1=Main Branch, 2=Sub Branch');
            $table->integer('branch_supported_vehicle_type')->nullable()->comment('1=only cars, 2=only bikes, 3=both cars and bikes');
            $table->text('branch_services')->nullable()->comment('list of services provided by the branch in separated by comma');
            $table->integer('country_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->text('address')->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->string('whatsapp_no', 20)->nullable();
            $table->text('email')->nullable();
            $table->text('short_description')->nullable();
            $table->text('branch_map')->nullable()->comment('Google map link');
            $table->string('map_latitude', 20)->nullable();
            $table->string('map_longitude', 20)->nullable();
            $table->string('map_city', 100)->nullable();
            $table->string('map_state', 100)->nullable();
            $table->string('map_district', 100)->nullable();
            $table->integer('is_active')->nullable()->comment('1=active,2=inactive,3=deleted');
            $table->integer('is_admin_approved')->nullable()->comment('1=approved,2=pending,3=rejected');
            $table->timestamp('admin_approved_dt')->nullable()->comment('date and time when the branch is approved by admin');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('branches');
    }
};
