<?php

namespace App\Services;

use App\Models\Vehicles;
use App\Repositories\VehicleRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\VehicleImages;


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
                $vehicle->images()->createMany($images);
            }

            return $vehicle;
        });
    }

    public function storeImagesNewVehicle($vehicleId, array $data) {
        return $this->vehicleRepo->storeImagesNewVehicle($vehicleId, $data);
    }

    public function updateVehicledetails(Vehicles $vehicle, array $data, array $images = []): Vehicles {

        return DB::transaction(function () use ($vehicle, $data, $images) {
            $updatedVehicle = $this->vehicleRepo->updateVehicledetails($vehicle, $data);

            if (!empty($images)) {
                $vehicle->images()->updateOrCreate(
                    ['vehicle_id' => $vehicle->id],
                    $images
                );
            }

            return $updatedVehicle;
        });
    }


    public function updateVehicleImages(int $vehicleId, array $data): VehicleImages {
        return $this->vehicleRepo->updateVehicleImages($vehicleId, $data);
    }

    public function deleteVehicle(Vehicles $vehicle) {
        $this->vehicleRepo->delete($vehicle);
    }

    // fetch all vahicle makes
    public function getAllMakes($vehicleType) {
        return $this->vehicleRepo->getAllMakes($vehicleType);
    }

    // fetch all vehicle models for a make & vehicle type
    public function getVehicleMakeModels($cmp_id, $vehicleType) {
        return $this->vehicleRepo->getVehicleMakeModels($cmp_id, $vehicleType);
    }

    // fetch all vehicle variants for a model
    public function getVehicleModelVariants($model_id) {
        return $this->vehicleRepo->getVehicleModelVariants($model_id);
    }

    public function getVehicleBodyTypes($vehicleType) {
        return $this->vehicleRepo->getVehicleBodyTypes($vehicleType);
    }

    public function getCountries() {
        return $this->vehicleRepo->getCountries();
    }

    public function getStates($countryId) {
        return $this->vehicleRepo->getStates($countryId);
    }

    public function getCities($stateId) {
        return $this->vehicleRepo->getCities($stateId);
    }

    public function getRTOsByState($stateId) {
        return $this->vehicleRepo->getRTOsByState($stateId);
    }

    public function getVehicleFuelTypes() {
        return $this->vehicleRepo->getVehicleFuelTypes();
    }

    public function getVehicleTransmissionTypes() {
        return $this->vehicleRepo->getVehicleTransmissionTypes();
    }
}
