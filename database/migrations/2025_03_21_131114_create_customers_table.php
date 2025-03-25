<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('contact_no', 25)->unique();

            // Optional fields marked as nullable
            $table->string('whatsapp_no', 25)->nullable();
            $table->text('profile_image')->nullable();
            $table->text('address')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->unsignedInteger('zipcode')->nullable();
            $table->unsignedInteger('otp')->nullable();
            $table->tinyInteger('otp_status')->nullable();
            $table->unsignedInteger('is_active')->default(1)->comment('1=active,2=inactive,3=deleted');

            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('customers');
    }
};
