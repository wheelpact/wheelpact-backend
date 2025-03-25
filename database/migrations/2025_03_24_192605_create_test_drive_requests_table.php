<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('test_drive_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->default(0)->comment("logged in customer id, or '0' as direct request");
            $table->string('customer_name', 30);
            $table->string('customer_phone', 15);
            $table->text('customer_email');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->date('dateOfVisit');
            $table->enum('timeOfVisit', ['1', '2', '3'])->comment("1 -> Morning (11 am - 1 pm), 2 -> Afternoon (1 pm - 4 pm), 3 -> Evening (4 pm - 8 pm)");
            $table->text('comments')->nullable();
            $table->string('license_file_path', 100)->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed', 'canceled'])->default('pending');
            $table->enum('is_active', ['0', '1'])->default('1');
            $table->text('reason_selected')->nullable();
            $table->text('dealer_comments')->nullable();
            $table->unsignedBigInteger('update_by')->nullable();

            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('test_drive_request');
    }
};
