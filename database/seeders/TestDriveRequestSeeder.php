<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TestDriveRequest;
use PHPUnit\Event\Code\Test;

class TestDriveRequestSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        TestDriveRequest::factory()->count(10)->create();
    }
}
