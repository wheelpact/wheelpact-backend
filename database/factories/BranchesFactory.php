<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branches>
 */
class BranchesFactory extends Factory {
    protected $table = 'branches';
    protected $fillable = [
        'dealer_id',
        'name',
        'branch_banner1',
        'branch_banner2',
        'branch_banner3',
        'branch_thumbnail',
        'branch_logo',
        'branch_type',
        'branch_supported_vehicle_type',
        'branch_services',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'contact_number',
        'whatsapp_no',
        'email',
        'short_description',
        'branch_map',
        'map_latitude',
        'map_longitude',
        'map_city',
        'map_state',
        'map_district',
        'is_active',
        'is_admin_approved',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            //
        ];
    }
}
