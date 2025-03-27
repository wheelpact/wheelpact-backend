<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'contact_no' => $this->faker->unique()->phoneNumber(),
            'whatsapp_no' => $this->faker->optional()->phoneNumber(),
            'profile_image' => $this->faker->optional()->imageUrl(),
            'address' => $this->faker->optional()->address(),
            'country_id' => $this->faker->optional()->numberBetween(1, 250),
            'state_id' => $this->faker->optional()->numberBetween(1, 500),
            'city_id' => $this->faker->optional()->numberBetween(1, 1000),
            'zipcode' => $this->faker->optional()->randomNumber(5, true),
            'otp' => $this->faker->optional()->randomNumber(6, true),
            'otp_status' => $this->faker->optional()->numberBetween(0, 1),
            'is_active' => $this->faker->randomElement([1, 2, 3]), // 1=active, 2=inactive, 3=deleted
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }
}
