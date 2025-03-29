<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UsersCredentials;

class UserscredentialsSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        UsersCredentials::create([
            'user_id' => 1,
            'password' => bcrypt('wheelpactAdmin@321$'), // Use bcrypt for password hashing
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

    }
}
