<?php

namespace App\Services;

use App\Models\Vehicles;
use App\Repositories\VehicleRepository;
use Illuminate\Support\Facades\DB;

class VehicleService {
    protected $vehicleRepo;

    public function __construct(VehicleRepository $vehicleRepo) {
        $this->vehicleRepo = $vehicleRepo;
    }

    public function listVehicles($user) {
        return $this->vehicleRepo->listVehiclesByUser($user, 5);
    }

    public function createVehicle(array $data, $images = []) {
        return DB::transaction(function () use ($data, $images) {
            $vehicle = $this->vehicleRepo->create($data);

            if (!empty($images)) {
                // handle vehicleimages table logic
                $vehicle->images()->create($images); // assuming hasOne relation
            }

            return $vehicle;
        });
    }

    public function updateVehicle(Vehicles $vehicle, array $data, $images = []) {
        return DB::transaction(function () use ($vehicle, $data, $images) {
            $this->vehicleRepo->update($vehicle, $data);

            if (!empty($images)) {
                $vehicle->images()->updateOrCreate(['vehicle_id' => $vehicle->id], $images);
            }

            return $vehicle;
        });
    }

    public function deleteVehicle(Vehicles $vehicle) {
        $this->vehicleRepo->delete($vehicle);
    }
}
