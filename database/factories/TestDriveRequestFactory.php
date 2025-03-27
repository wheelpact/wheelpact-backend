<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestDriveRequest>
 */
class TestDriveRequestFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'customer_id' => $this->faker->numberBetween(1, 10),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->unique()->safeEmail(),
            'customer_phone' => $this->faker->unique()->phoneNumber(),
            'branch_id' => $this->faker->numberBetween(1, 10),
            'vehicle_id' => $this->faker->numberBetween(1, 10),
            'dateOfVisit' => $this->faker->dateTimeBetween('now', '+1 month'),
            'timeoFVisit' => $this->faker->randomElement([1, 2, 3]), //1 -> Morning (11 am - 1 pm), 2 -> Afternoon (1 pm - 4 pm), 3 -> Evening (4 pm - 8 pm)
            'comments' => $this->faker->optional()->sentence(),
            'license_file_path' => $this->faker->optional()->imageUrl(),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected', 'completed']),
            'is_active' => $this->faker->randomElement(['0', '1']), // 0 = InActive, 1 = Active
            'reason_selected' => $this->faker->optional()->sentence(),
            'dealer_comments' => $this->faker->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
