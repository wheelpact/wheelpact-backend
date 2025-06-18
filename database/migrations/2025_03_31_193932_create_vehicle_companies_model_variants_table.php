<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('vehiclecompaniesmodelvariants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id')->constrained('vehicle_company_models')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('vehiclecompaniesmodelvariants');
    }
};
