<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

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
use App\Models\VehicleImages;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

use App\Models\Indiarto;

class VehicleRepository {
    /**
     * List vehicles for a user with pagination.
     *
     * @param $user
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
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

    /**
     * Create a new vehicle record.
     *
     * @param array $data
     * @return Vehicles
     */
    public function create(array $data) {
        $filteredData = $this->filterDataByVehicleType($data);
        return Vehicles::create($filteredData);
    }

    public function storeImagesNewVehicle($vehicleId, array $data) {

        $imageFields = Vehicles::imageFields();

        $imageData = ['vehicle_id' => $vehicleId];

        foreach ($imageFields as $field) {
            if (isset($data[$field]) && $data[$field] instanceof \Illuminate\Http\UploadedFile) {
                $image = $data[$field];

                // Use vehicle_unique_id from $data
                $folder = $data['unique_id'] ?? 'VEH-' . now()->timestamp;

                $path = $image->store("vehicle_images/{$folder}", 'public');
                $imageData[$field] = $path;
            }
        }

        $vehicleImages = VehicleImages::create($imageData);

        return response()->json([
            'message' => 'Images uploaded successfully.',
            'data' => $vehicleImages
        ], 201);
    }

    private function filterDataByVehicleType(array $data): array {

        $vehicleType = $data['vehicle_type'] ?? null;

        $prefix = match ($vehicleType) {
            1 => 'WVEH-C-',
            2 => 'WVEH-B-',
            default => 'WVEH-U-', // U for unknown or unclassified
        };

        $data['unique_id'] = $prefix . now()->format('YmdHis') . '-' . Str::upper(Str::random(5));

        $sharedFields = [
            'unique_id',
            'vehicle_type',
            'branch_id',
            'cmp_id',
            'model_id',
            'variant_id',
            'fuel_type',
            'mileage',
            'kms_driven',
            'owner',
            'color_id',
            'featured_status',
            'onsale_status',
            'manufacture_year',
            'registration_year',
            'registered_state_id',
            'rto',
            'insurance_type',
            'accidental_status',
            'flooded_status',
            'last_service_kms',
            'is_active',
            'created_by',
            'thumbnail_url',
            'transmission_id',
            'body_type',
        ];

        $carFields = [
            'car_no_of_airbags',
            'car_central_locking',
            'car_seat_upholstery',
            'car_sunroof',
            'car_integrated_music_system',
            'car_rear_ac',
            'car_outside_rear_view_mirrors',
            'car_power_windows',
            'car_engine_start_stop',
            'car_headlamps',
            'car_power_steering',
        ];

        $bikeFields = [
            'bike_headlight_type',
            'bike_odometer',
            'bike_drl',
            'bike_mobile_connectivity',
            'bike_gps_navigation',
            'bike_usb_charging_port',
            'bike_low_battery_indicator',
            'bike_under_seat_storage',
            'bike_speedometer',
            'bike_stand_alarm',
            'bike_low_fuel_indicator',
            'bike_low_oil_indicator',
            'bike_start_type',
            'bike_kill_switch',
            'bike_break_light',
            'bike_turn_signal_indicator',
        ];

        if ($vehicleType == 1) {
            $fields = array_merge($sharedFields, $carFields);
        } elseif ($vehicleType == 2) {
            $fields = array_merge($sharedFields, $bikeFields);
        } else {
            $fields = $sharedFields;
        }

        return array_filter($data, fn($key) => in_array($key, $fields), ARRAY_FILTER_USE_KEY);
    }

    // Update existing vehicle details
    public function updateVehicledetails(Vehicles $vehicle, array $data): Vehicles {
        Log::info('VehicleRepository@update called', [
            'vehicle_id' => $vehicle->id,
            'original' => $vehicle->toArray(),
            'incoming_data' => $data,
        ]);

        // Fill the model with new data
        $vehicle->fill($data);

        // Check what has changed (dirty attributes)
        $dirty = $vehicle->getDirty();

        if (!empty($dirty)) {
            Log::info('Dirty attributes to be updated:', $dirty);

            $vehicle->save();

            Log::info('Vehicle updated successfully.', [
                'vehicle_id' => $vehicle->id,
                'updated_attributes' => $dirty,
            ]);
        } else {
            Log::info('No changes detected. Vehicle not updated.', [
                'vehicle_id' => $vehicle->id,
            ]);
        }

        return $vehicle;
    }

    // Update images for a existing vehicle
    public function updateVehicleImages(int $vehicleId, array $data): VehicleImages {
        try {
            // Ensure vehicle exists
            $vehicle = Vehicles::findOrFail($vehicleId);

            // Find or create associated vehicle_images record
            $vehicleImages = VehicleImages::firstOrNew(['vehicle_id' => $vehicleId]);

            // Get list of image fields
            $imageFields = VehicleImages::imageFields();

            // Define image folder based on vehicle's unique ID
            $folder = $data['unique_id'] ?? 'VEH-' . now()->timestamp;

            foreach ($imageFields as $field) {
                if (isset($data[$field]) && $data[$field] instanceof UploadedFile) {
                    try {
                        $path = $data[$field]->store("vehicle_images/{$folder}", 'public');
                        $vehicleImages->{$field} = $path;
                    } catch (Exception $uploadException) {
                        Log::error("Failed to upload {$field}: " . $uploadException->getMessage());
                        throw new Exception("Image upload failed for field '{$field}'.");
                    }
                }
            }

            $vehicleImages->vehicle_id = $vehicle->id;
            $vehicleImages->save();

            return $vehicleImages;
        } catch (ModelNotFoundException $e) {
            throw new Exception('Vehicle not found.');
        } catch (Exception $e) {
            Log::error('Failed to update vehicle images: ' . $e->getMessage());
            throw $e; // Re-throw to be handled by controller or service
        }
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
