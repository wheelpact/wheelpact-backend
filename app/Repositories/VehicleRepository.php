<?php

namespace App\Repositories;

use App\Models\Vehicles;

class VehicleRepository {
    public function listVehiclesByUser($user, $perPage = null) {
        $perPage = $perPage ?? Config::get('constants.PER_PAGE_RECORDS');

        return $user->vehicles()
            ->with(
                [
                    'vehicleCompany:id,cmp_name',
                    'vehicleType:id,name',
                    'model:id,model_name',
                    'variant:id,name',
                    'fuelType:id,name',
                    'bodyType:id,title',
                    'branches:id,city_id,name,contact_number',
                    'branches.country:id,name',
                    'branches.state:id,name',
                    'branches.city:id,name',
                    'indiarto:id,rto_state_code,place',
                    'transmission:id,title'

                ]
            )
            ->paginate($perPage);
    }

    public function create(array $data) {
        return Vehicles::create($data);
    }

    public function update(Vehicles $vehicle, array $data) {
        $vehicle->update($data);
        return $vehicle;
    }

    public function delete(Vehicles $vehicle) {
        $vehicle->delete();
    }
}
