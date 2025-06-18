<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('vehicle_body_types', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50)->nullable();
            $table->enum('vehicle_type', ['1', '2', '3', ''])->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('img_body_type', 100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('vehicle_body_types');
    }
};
