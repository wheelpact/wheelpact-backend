<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {

        // Create a default admin customer (if applicable)
        User::create([
            'name' => 'Super Admin',
            'user_code' => '123admin',
            'email' => 'SuperAdmin@wheelpact.com',
            'addr_residential' => '123 Admin Street, City, Country',
            'addr_permanent' => '123 Admin Street, City, Country',
            'date_of_birth' => '2000-01-01',
            'gender' => '1',
            'profile_image' => '',
            'country_id' => 1,
            'state_id' => 1,
            'city_id' => 1,
            'zipcode' => '12345',
            'contact_no' => '1234567890',
            'whatsapp_no' => '1234567890',
            'social_fb_link' => '',
            'social_twitter_link' => '',
            'social_linkedin_link' => '',
            'social_skype_link' => '',
            'role_id' => 1, // Super Admin
            'otp' => '123456',
            'otp_status' => 0,
            'is_active' => 1,
            'reset_token' => '',
            'token_expiration' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}
