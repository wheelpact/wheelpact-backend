<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('vehiclecompanies', function (Blueprint $table) {
            $table->id();
            $table->string('cmp_name')->nullable();
            $table->string('cmp_logo')->nullable();
            $table->string('cmp_cat')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('vehiclecompanies');
    }
};
