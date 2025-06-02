<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('vehiclecompanymodels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cmp_id')->constrained('vehiclecompanies')->onDelete('cascade');
            $table->string('model_name')->nullable();
            $table->string('cmp_cat')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('vehiclecompanymodels');
    }
};
