<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('userscredentials', function (Blueprint $table) {
            $table->increments('id'); // INT AUTO_INCREMENT PRIMARY KEY
            $table->unsignedBigInteger('user_id'); // Reference to users.id
            $table->text('password');
            $table->integer('is_active')->nullable()->comment('1=active,2=inactive,3=deleted');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('userscredentials');
    }
};
