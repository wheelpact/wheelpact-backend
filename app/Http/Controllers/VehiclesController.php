<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\Dealer\StoreVehicleRequest;
use App\Http\Requests\Dealer\UpdateVehicleRequest;
use App\Resources\VehicleResource;
use App\Services\VehicleService;
use App\Models\Vehicles;

use Exception;

class VehiclesController extends Controller {
    protected $vehicleService;

    // vehicleService is injected to handle vehicle-related operations
    // such as listing, creating, updating, and deleting vehicles.
    /**
     * VehiclesController constructor.
     *
     * @param VehicleService $vehicleService
     */
    public function __construct(VehicleService $vehicleService) {
        $this->vehicleService = $vehicleService;
    }

    /**
     * @OA\Get(
     *     path="/api/dealerApi/v1/dealerVechileList",
     *     summary="List Vehicles - Dealer",
     *     description="Returns a paginated list of vehicles for the authenticated dealer. Requires Bearer Token authentication.",
     *     operationId="listDealerVehicles",
     *     tags={"Dealer: Vehicles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number for pagination",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of vehicles",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Vehicle")),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized - Invalid or missing Bearer Token")
     * )
     */

    public function index(Request $request) {
        try {
            DB::enableQueryLog(); // Start SQL query logging

            $user = auth()->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized. Please login.'], 401);
            }

            $vehicles = $this->vehicleService->listVehicles($user);

            // Optional: log SQL queries
            Log::info('Executed Queries:', DB::getQueryLog());

            return VehicleResource::collection($vehicles);
        } catch (Exception $e) {
            Log::error('Error fetching vehicles: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'An error occurred while fetching vehicles.',
                'error' => $e->getMessage(), // Optional: remove this in production
            ], 500);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/dealerApi/v1/vehicles",
     *     summary="Add New Vehicle - Dealer",
     *     description="Add a new vehicle for logged-in dealer.",
     *     operationId="storeVehicle",
     *     tags={"Dealer: Vehicles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreVehicleRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vehicle created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Vehicle")
     *     ),
     *     @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function store(StoreVehicleRequest $request) {
        $vehicle = $this->vehicleService->createVehicle($request->validated(), $request->only(array_keys($request->all())));
        return new VehicleResource($vehicle);
    }

    /**
     * @OA\Put(
     *     path="/api/dealerApi/v1/vehicles/{vehicle}",
     *     summary="Update vehicle",
     *     description="Update a specific vehicle details",
     *     operationId="updateVehicle",
     *     tags={"Dealer: Vehicles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="vehicle",
     *         in="path",
     *         required=true,
     *         description="Vehicle ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateVehicleRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Vehicle")
     *     )
     * )
     */

    public function update(UpdateVehicleRequest $request, Vehicles $vehicle) {
        $vehicle = $this->vehicleService->updateVehicle($vehicle, $request->validated(), $request->only(array_keys($request->all())));
        return new VehicleResource($vehicle);
    }

    /**
     * @OA\Delete(
     *     path="/api/dealerApi/v1/vehicles/{vehicle}",
     *     summary="Delete Vehicle",
     *     description="Soft deletes a vehicle by ID",
     *     operationId="deleteVehicle",
     *     tags={"Dealer: Vehicles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="vehicle",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vehicle soft deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Vehicle not found")
     * )
     */
    public function destroy(Vehicles $vehicle) {
        $this->vehicleService->deleteVehicle($vehicle);
        return response()->json(['message' => 'Vehicle soft deleted successfully']);
    }
}
