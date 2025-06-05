<?php

namespace App\Repositories;

use App\Models\Vehicles;
use App\Models\VehicleType;
use App\Models\VehicleCompanies as VehicleCompany;
use App\Models\VehicleCompanyModel;
use App\Models\VehicleCompaniesModelVariants;
use App\Models\VehicleBodyTypes;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\FuelTypes;
use App\Models\Transmission;

use Illuminate\Support\Facades\Config;

use App\Models\Indiarto;

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

    public function getAllMakes($vehicleType) {
        return VehicleCompany::select('id', 'cmp_name as name')
            ->when($vehicleType, function ($query, $vehicleType) {
                $query->where(function ($q) use ($vehicleType) {
                    $q->where('cmp_cat', $vehicleType)
                        ->orWhere('cmp_cat', 3);
                });
            })
            ->orderBy('cmp_name')
            ->get();
    }

    /**
     * Get all models for a make (company) with optional vehicle type filtering.
     */
    public function getVehicleMakeModels($cmp_id, $vehicleType = null) {
        return VehicleCompany::with(['models' => function ($q) use ($vehicleType) {
            $q->select('id', 'cmp_id', 'model_name')
                ->when($vehicleType, fn($q) => $q->where('cmp_cat', $vehicleType))
                ->orderBy('model_name');
        }])
            ->find($cmp_id)?->models ?? collect();
    }

    public function getVehicleModelVariants($model_id) {
        return VehicleCompanyModel::with(['variants' => function ($q) {
            $q->select('id', 'model_id', 'name');
        }])
            ->find($model_id)?->variants ?? collect();
    }

    public function getVehicleBodyTypes($vehicleType = null) {
        return VehicleBodyTypes::select('id', 'title', 'img_body_type')
            ->where('is_active', 1)
            ->when($vehicleType, function ($query, $vehicleType) {
                $query->where('vehicle_type', $vehicleType);
            })
            ->orderBy('title')
            ->get();
    }

    public function getCountries() {
        return Country::select('id', 'shortname', 'name')
            ->distinct()
            ->orderBy('name')
            ->get();
    }

    public function getStates($countryId = null) {
        return State::select('id', 'short_code', 'name')
            ->when($countryId, function ($query, $countryId) {
                $query->where('country_id', $countryId);
            })
            ->orderBy('name')
            ->get();
    }

    public function getCities($stateId) {
        return City::select('id', 'name')
            ->where('state_id', $stateId)
            ->orderBy('name')
            ->get();
    }

    public function getRTOsByState($stateId) {
        return Indiarto::select('id', 'rto_state_code', 'place')
            ->where('state_id', $stateId)
            ->orderBy('rto_state_code')
            ->get();
    }

    public function getVehicleFuelTypes() {
        return FuelTypes::select('id', 'name')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
    }

    public function getVehicleTransmissionTypes() {
        return Transmission::select('id', 'title')
            ->orderBy('title')
            ->get();
    }
}
