<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VehicleService;

class FormDataController extends Controller {
    protected $vehicleService;

    public function __construct(VehicleService $vehicleService) {
        $this->vehicleService = $vehicleService;
    }

    /**
     * @OA\Get(
     *     path="/api/dealerApi/v1/form-data/getVehicleMakes",
     *     summary="Get All Vehicle Makes - Vehicle Type",
     *     description="Returns a list of vehicle companies (makes), optionally filtered by vehicle type (1=Car, 2=Bike, 3=Both).",
     *     operationId="getVehicleMakes",
     *     tags={"Form Data"},
     *     @OA\Parameter(
     *         name="vehicle_type",
     *         in="query",
     *         required=false,
     *         description="Vehicle type filter: 1=Car, 2=Bike, 3=Both",
     *         @OA\Schema(type="integer", enum={1, 2, 3})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of vehicle makes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="name", type="string", example="Hyundai")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The vehicle_type field is required.")
     *         )
     *     )
     * )
     */
    public function getVehicleMakes(Request $request) {
        $validated = $request->validate([
            'vehicle_type' => 'required|in:1,2,3'
        ]);

        $vehicleType = $validated['vehicle_type'];

        $makes = $this->vehicleService->getAllMakes($vehicleType);

        return response()->json($makes);
    }


    /**
     * @OA\Get(
     *     path="/api/dealerApi/v1/form-data/getVehicleMakeModels",
     *     summary="Get Models by Vehicle Make",
     *     description="Fetches all vehicle models based on selected company (make) and optional vehicle type.",
     *     operationId="getVehicleMakeModels",
     *     tags={"Form Data"},
     *     @OA\Parameter(
     *         name="cmp_id",
     *         in="query",
     *         required=true,
     *         description="ID of the vehicle company (make)",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="vehicle_type",
     *         in="query",
     *         required=false,
     *         description="Optional: Vehicle type filter (1=Car, 2=Bike, 3=Both)",
     *         @OA\Schema(type="integer", enum={1, 2, 3})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of vehicle models",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=101),
     *                 @OA\Property(property="cmp_id", type="integer", example=2),
     *                 @OA\Property(property="model_name", type="string", example="Swift Dzire")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function getVehicleMakeModels(Request $request) {
        $request->validate([
            'cmp_id' => 'required|integer|exists:vehiclecompanies,id',
            'vehicle_type' => 'nullable|in:1,2,3'
        ]);

        $cmp_id = $request->query('cmp_id');
        $vehicleType = $request->query('vehicle_type');

        $vehicleModels = $this->vehicleService->getVehicleMakeModels($cmp_id, $vehicleType);

        return response()->json($vehicleModels);
    }

    /**
     * @OA\Get(
     *     path="/api/dealerApi/v1/form-data/getVehicleModelVariants",
     *     summary="Get Variants by Vehicle Model",
     *     description="Fetches all variants for a specific vehicle model.",
     *     operationId="getVehicleModelVariants",
     *     tags={"Form Data"},
     *     @OA\Parameter(
     *         name="model_id",
     *         in="query",
     *         required=true,
     *         description="ID of the vehicle model",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of vehicle model variants",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=201),
     *                 @OA\Property(property="model_id", type="integer", example=5),
     *                 @OA\Property(property="name", type="string", example="SX (O) Turbo")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function getVehicleModelVariants(Request $request) {
        $request->validate([
            'model_id' => 'required|integer|exists:vehiclecompaniesmodels,id'
        ]);

        $model_id = $request->query('model_id');

        $variants = $this->vehicleService->getVehicleModelVariants($model_id);

        return response()->json($variants);
    }

    /**
     * @OA\Get(
     *     path="/api/dealerApi/v1/form-data/getVehicleBodyTypes",
     *     summary="Get Body Types by Vehicle Type",
     *     description="Fetches all body types based on vehicle type (1=Car, 2=Bike, 3=Both).",
     *     operationId="getVehicleBodyTypes",
     *     tags={"Form Data"},
     *     @OA\Parameter(
     *         name="vehicle_type",
     *         in="query",
     *         required=true,
     *         description="Vehicle type filter: 1=Car, 2=Bike, 3=Both",
     *         @OA\Schema(type="integer", enum={1, 2, 3})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of body types",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="SUV"),
     *                 @OA\Property(property="img_body_type", type="string", example="/images/body_types/suv.png")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function getVehicleBodyTypes(Request $request) {

        // Validate that 'vehicle_type' is required and is one of allowed values
        $validated = $request->validate([
            'vehicle_type' => 'required|in:1,2,3',  // 	1=car, 2=bike, 3=both
        ]);

        $vehicleType = $validated['vehicle_type'];

        $bodyTypes = $this->vehicleService->getVehicleBodyTypes($vehicleType);

        return response()->json($bodyTypes);
    }

    /**
     * Get country list
     * Returns a list of all countries.
     * @OA\Get(
     *     path="/api/dealerApi/v1/form-data/getCountries",
     *     operationId="getCountries",
     *     tags={"Form Data"},
     *     summary="Get list of countries",
     *     description="Returns a list of all countries",
     *     @OA\Response(
     *         response=200,
     *         description="List of countries retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=101),
     *                 @OA\Property(property="name", type="string", example="India"),
     *                 @OA\Property(property="short_code", type="string", example="IN")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No countries found.")
     *         )
     *     )
     * )
     */
    public function getCountries() {
        // Fetch all countries from the database
        $countries = $this->vehicleService->getCountries();
        if ($countries->isEmpty()) {
            return response()->json(['message' => 'No countries found.'], 422);
        }
        return response()->json($countries);
    }

    /**
     * Get list of states by country ID
     *
     * Returns all available states for a given country.
     *
     * @OA\Get(
     *     path="/api/dealerApi/v1/form-data/getStates",
     *     operationId="getStates",
     *     tags={"Form Data"},
     *     summary="Get list of states",
     *     description="Returns list of states for a given country ID",
     *     @OA\Parameter(
     *         name="country_id",
     *         in="query",
     *         required=true,
     *         description="ID of the country",
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of states retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Maharashtra")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The country_id field is required.")
     *         )
     *     )
     * )
     */
    public function getStates(Request $request) {
        $validated = $request->validate([
            'country_id' => 'required|integer|exists:countries,id',
        ]);

        $countryId = $validated['country_id'];

        $states = $this->vehicleService->getStates($countryId);
        return response()->json($states);
    }

    /**
     * Get list of cities by state ID
     *
     * Returns all available cities for a given state ID.
     *
     * @OA\Get(
     *     path="/api/dealerApi/v1/form-data/getCities",
     *     operationId="getCities",
     *     tags={"Form Data"},
     *     summary="Get list of cities",
     *     description="Returns list of cities for a given state ID",
     *     @OA\Parameter(
     *         name="state_id",
     *         in="query",
     *         required=true,
     *         description="ID of the state",
     *         @OA\Schema(type="integer", example=21)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of cities retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=203),
     *                 @OA\Property(property="name", type="string", example="Mumbai")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The state_id field is required.")
     *         )
     *     )
     * )
     */
    public function getCities(Request $request) {
        // This method should return a list of cities based on the provided state ID
        // Assuming you have a method in VehicleService to fetch cities
        $validated = $request->validate([
            'state_id' => 'required|integer|exists:states,id',
        ]);

        $stateId = $validated['state_id'];

        // Fetch cities based on state ID
        $cities = $this->vehicleService->getCities($stateId);

        return response()->json($cities);
    }

    /**
     * Get RTOs by state ID
     *
     * Returns a list of RTOs based on the provided state ID.
     *
     * @OA\Get(
     *     path="/api/dealerApi/v1/form-data/getRTOsByState",
     *     operationId="getRTOsByState",
     *     tags={"Form Data"},
     *     summary="Get RTOs by state",
     *     description="Returns list of RTOs for a given state ID",
     *     @OA\Parameter(
     *         name="state_id",
     *         in="query",
     *         required=true,
     *         description="ID of the state",
     *         @OA\Schema(type="integer", example=21)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of RTOs retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Mumbai RTO")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The state_id field is required.")
     *         )
     *     )
     * )
     */
    public function getRTOsByState(Request $request) {
        // This method should return a list of RTOs based on the provided state ID
        // Assuming you have a method in VehicleService to fetch RTOs
        $validated = $request->validate([
            'state_id' => 'required|integer|exists:states,id',
        ]);

        $stateId = $validated['state_id'];

        // Fetch RTOs based on state ID
        $rtos = $this->vehicleService->getRTOsByState($stateId);

        return response()->json($rtos);
    }

    /**
     * Get all vehicle fuel types
     *
     * Returns a list of all available fuel types for vehicles.
     *
     * @OA\Get(
     *     path="/api/dealerApi/v1/form-data/getVehicleFuelTypes",
     *     operationId="getVehicleFuelTypes",
     *     tags={"Form Data"},
     *     summary="Get list of vehicle fuel types",
     *     description="Returns a list of all vehicle fuel types",
     *     @OA\Response(
     *         response=200,
     *         description="List of vehicle fuel types retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Petrol")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No fuel types found.")
     *         )
     *     )
     * )
     */
    public function getVehicleFuelTypes() {
        // Fetch all fuel types from the database
        $fuelTypes = $this->vehicleService->getVehicleFuelTypes();
        if ($fuelTypes->isEmpty()) {
            return response()->json(['message' => 'No fuel types found.'], 422);
        }
        return response()->json($fuelTypes);
    }

    /**
     * Get all vehicle transmission types
     *
     * Returns a list of all available transmission types for vehicles.
     *
     * @OA\Get(
     *     path="/api/dealerApi/v1/form-data/getVehicleTransmissionTypes",
     *     operationId="getVehicleTransmissionTypes",
     *     tags={"Form Data"},
     *     summary="Get list of vehicle transmission types",
     *     description="Returns a list of all vehicle transmission types",
     *     @OA\Response(
     *         response=200,
     *         description="List of vehicle transmission types retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Automatic")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No transmission types found.")
     *         )
     *     )
     * )
     */
    public function getVehicleTransmissionTypes() {
        // Fetch all transmission types from the database
        $transmissionTypes = $this->vehicleService->getVehicleTransmissionTypes();
        if ($transmissionTypes->isEmpty()) {
            return response()->json(['message' => 'No transmission types found.'], 422);
        }
        return response()->json($transmissionTypes);
    }
}
