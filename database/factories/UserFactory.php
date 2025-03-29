<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory {
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => fake()->name(),
            'addr_residential' => fake()->address(),
            'addr_permanent' => fake()->address(),
            'date_of_birth' => fake()->date(),
            'gender' => fake()->random_int(1, 2),
            'profile_image' => fake()->imageUrl(),
            'country_id' => fake()->numberBetween(1, 250),
            'state_id' => fake()->numberBetween(1, 500),
            'city_id' => fake()->numberBetween(1, 1000),
            'zipcode' => fake()->randomNumber(5, true),
            'contact_no' => fake()->unique()->phoneNumber(),
            'whatsapp_no' => fake()->optional()->phoneNumber(),
            'social_fb_link' => fake()->optional()->url(),
            'social_twitter_link' => fake()->optional()->url(),
            'social_linkedin_link' => fake()->optional()->url(),
            'social_skype_link' => fake()->optional()->url(),
            'role_id' => fake()->numberBetween(2, 5),
            'otp' => fake()->randomNumber(6, true),
            'is_active' => fake()->randomElement([1, 2, 3]), // 1=active, 2=inactive, 3=deleted
            'reset_token' => fake()->optional()->uuid(),
            'token_expiration' => fake()->optional()->dateTime(),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
