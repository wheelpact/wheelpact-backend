<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
       // Create a default customer
        Customer::create([
            'name' => 'Jhon Customer',
            'email' => 'test@customer.com',
            'contact_no' => '9999999999',
            'whatsapp_no' => '9999999999',
            'profile_image' => null,
            'address' => '123 Admin Street, City, Country',
            'country_id' => 101,
            'state_id' => null,
            'city_id' => null,
            'zipcode' => 123456,
            'otp' => null,
            'otp_status' => 0,
            'is_active' => 1, // 1=active
        ]);

        // Generate 10 random customers using the factory
        Customer::factory()->count(10)->create();
    }
}
