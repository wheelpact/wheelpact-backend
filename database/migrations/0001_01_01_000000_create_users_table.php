<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->rememberToken();
        //     $table->timestamps();
        // });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_code', 20);
            $table->string('name', 100);
            $table->text('email');
            $table->text('addr_residential');
            $table->text('addr_permanent');
            $table->date('date_of_birth');
            $table->integer('gender')->comment('1=male,2=female');
            $table->text('profile_image');
            $table->integer('country_id');
            $table->integer('state_id');
            $table->integer('city_id');
            $table->integer('zipcode');
            $table->string('contact_no', 20);
            $table->string('whatsapp_no', 25);
            $table->text('social_fb_link');
            $table->text('social_twitter_link');
            $table->text('social_linkedin_link');
            $table->text('social_skype_link');
            $table->integer('role_id');
            $table->integer('otp')->length(6);
            $table->tinyInteger('otp_status')->comment('1 = Otp generated for registration / reset password, 0 = OTP verified and reset to 0');
            $table->integer('is_active')->comment('1=active,2=inactive,3=deleted');
            $table->text('reset_token');
            $table->dateTime('token_expiration')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
