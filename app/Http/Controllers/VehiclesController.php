<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Requests\Dealer\StoreVehicleRequest;
use App\Http\Requests\Dealer\UpdateVehicleRequest;
use App\Resources\VehicleResource;
use App\Services\VehicleService;

use App\Models\Vehicles;
use App\Models\VehicleImages;

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
     *     path="/api/dealerApi/v1/vehicles",
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
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized. Please login.'], 401);
            }

            $vehicles = $this->vehicleService->listVehicles($user);

            return VehicleResource::collection($vehicles);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching vehicles.'
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/dealerApi/v1/vehicles",
     *     summary="Add New Vehicle - Dealer",
     *     description="Add details of new vehicle (Car or Bike) for the logged-in dealer.",
     *     operationId="storeVehicle",
     *     tags={"Dealer: Vehicles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={
     *                     "branch_id", "vehicle_type", "cmp_id", "model_id", "variant_id",
     *                     "fuel_type", "mileage", "kms_driven", "owner", "featured_status",
     *                     "onsale_status", "manufacture_year", "registration_year", "registered_state_id",
     *                     "rto", "insurance_type", "accidental_status", "flooded_status",
     *                     "last_service_kms", "is_active", "thumbnail_url"
     *                 },
     *                 @OA\Property(property="vehicle_type", type="integer", enum={1,2}, description="1=Car, 2=Bike", example=1),
     *                 @OA\Property(property="branch_id", type="integer", example=1),
     *                 @OA\Property(property="cmp_id", type="integer", example=10),
     *                 @OA\Property(property="model_id", type="integer", example=101),
     *                 @OA\Property(property="variant_id", type="integer", example=202),
     *                 @OA\Property(property="fuel_type", type="integer", example=1),
     *                 @OA\Property(property="mileage", type="number", example=18.5),
     *                 @OA\Property(property="kms_driven", type="integer", example=25000),
     *                 @OA\Property(property="owner", type="integer", example=1),
     *                 @OA\Property(property="featured_status", type="integer", enum={1,2}, example=1),
     *                 @OA\Property(property="onsale_status", type="integer", enum={1,2}, example=1),
     *                 @OA\Property(property="manufacture_year", type="integer", example=2020),
     *                 @OA\Property(property="registration_year", type="integer", example=2021),
     *                 @OA\Property(property="registered_state_id", type="integer", example=14),
     *                 @OA\Property(property="rto", type="integer", example="539"),
     *                 @OA\Property(property="insurance_type", type="integer", enum={1,2}, example=1),
     *                 @OA\Property(property="insurance_validity", type="string", format="date", example="2026-05-31"),
     *                 @OA\Property(property="accidental_status", type="integer", enum={1,2}, example=2),
     *                 @OA\Property(property="flooded_status", type="integer", enum={1,2}, example=2),
     *                 @OA\Property(property="last_service_kms", type="integer", example=24000),
     *                 @OA\Property(property="is_active", type="integer", enum={1,2,3,4}, example=1),
     *                 @OA\Property(
     *                     property="thumbnail_url",
     *                     type="string",
     *                     format="binary",
     *                     description="Vehicle thumbnail image"
     *                 ),

     *                 @OA\Property(property="onsale_percentage", type="number", example=10.5),
     *                 @OA\Property(property="search_keywords", type="string", example="SUV, black, 2020"),
     *                 @OA\Property(property="last_service_date", type="string", format="date", example="2025-12-10"),

     *                 @OA\Property(property="regular_price", type="number", example=550000),
     *                 @OA\Property(property="selling_price", type="number", example=520000),
     *                 @OA\Property(property="pricing_type", type="integer", enum={1,2}, example=2),
     *                 @OA\Property(property="emi_option", type="integer", enum={1,2}, example=1),
     *                 @OA\Property(property="avg_interest_rate", type="number", example=10.5),
     *                 @OA\Property(property="tenure_months", type="integer", example=36),
     *                 @OA\Property(property="reservation_amt", type="integer", example=5000),

     *                 @OA\Property(property="is_admin_approved", type="string", enum={"0","1"}, example="0"),
     *                 @OA\Property(property="soldReason", type="string", example="Customer request"),
     *                 @OA\Property(property="updated_by", type="integer", example=12),

     *                 @OA\Property(property="transmission_id", type="integer", example=2),
     *                 @OA\Property(property="body_type", type="integer", example=3),

     *                 @OA\Property(property="car_no_of_airbags", type="integer", example=2),
     *                 @OA\Property(property="car_central_locking", type="integer", enum={1,2,3}),
     *                 @OA\Property(property="car_seat_upholstery", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_sunroof", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_integrated_music_system", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_rear_ac", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_outside_rear_view_mirrors", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_power_windows", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_engine_start_stop", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_headlamps", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_power_steering", type="integer", enum={1,2}),

     *                 @OA\Property(property="bike_headlight_type", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_odometer", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_drl", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_mobile_connectivity", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_gps_navigation", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_usb_charging_port", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_low_battery_indicator", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_under_seat_storage", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_speedometer", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_stand_alarm", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_low_fuel_indicator", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_low_oil_indicator", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_start_type", type="integer", enum={1,2,3}),
     *                 @OA\Property(property="bike_kill_switch", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_break_light", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_turn_signal_indicator", type="integer", enum={1,2})
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vehicle created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Vehicle")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */

    public function store(StoreVehicleRequest $request) {
        try {

            $data = $request->validated();

            // Assign the authenticated user's ID as the creator
            $data['created_by'] = AUTH::id();

            // Safe cast vehicle_type to int (since request values are strings by default)
            $vehicleType = (int) $request->input('vehicle_type');

            $prefix = match ($vehicleType) {
                1 => 'WVEH-C-',
                2 => 'WVEH-B-',
                default => 'WVEH-U-', // U for unknown or unclassified
            };

            $data['unique_id'] = $prefix . now()->format('YmdHis') . '-' . Str::upper(Str::random(5));

            // Handle file upload
            if ($request->hasFile('thumbnail_url')) {
                $file = $request->file('thumbnail_url');

                if ($file->isValid()) {
                    $data['thumbnail_url'] = $file->store('vehicle_thumbnails/' . $data['unique_id'], 'public');
                } else {
                    return response()->json([
                        'message' => 'Uploaded file is not valid.',
                    ], 422);
                }
            }

            // Delegate creation to service
            $vehicle = $this->vehicleService->createVehicle($data);

            return response()->json([
                'message' => 'Vehicle added successfully.',
                'data' => new VehicleResource($vehicle),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create vehicle.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/dealerApi/v1/vehicles/{vehicleId}/images",
     *     summary="Upload detailed vehicle images",
     *     description="Uploads all required vehicle image fields and stores them in the vehicleimages table.",
     *     tags={"Dealer: Vehicles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="vehicleId",
     *         in="path",
     *         required=true,
     *         description="ID of the vehicle",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"exterior_main_front_img"},
     *                 @OA\Property(property="exterior_main_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_right_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_left_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_tank_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_handlebar_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_headlight_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_tail_light_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_speedometer_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_exhaust_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_seat_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_roof_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_bonetopen_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_engine_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_diagnoal_right_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_diagnoal_right_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_diagnoal_left_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_diagnoal_left_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_rear_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_right_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_right_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_left_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_left_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_spare_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_tyrethread_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_tyrethread_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_tyrethread_right_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_tyrethread_right_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_tyrethread_left_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_tyrethread_left_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_underbody_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_underbody_rear_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_underbody_right_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_underbody_left_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_dashboard_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_infotainment_system_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_steering_wheel_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_odometer_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_gear_lever_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_pedals_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_front_cabin_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_mid_cabin_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_rear_cabin_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_driver_side_door_panel_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_driver_side_adjustment_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_boot_inside_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_boot_door_open_img", type="string", format="binary"),
     *                 @OA\Property(property="others_keys_img", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Images uploaded successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Images uploaded successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Image upload failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Image upload failed."),
     *             @OA\Property(property="error", type="string", example="Exception message")
     *         )
     *     )
     * )
     */

    public function storeVehicleImages(Request $request, $vehicleId) {
        try {
            // Check if vehicle exists
            $vehicle = Vehicles::find($vehicleId);
            if (!$vehicle) {
                return response()->json([
                    'message' => 'Vehicle not found.',
                ], 404);
            }

            // Check if images already exist
            $existingImages = VehicleImages::where('vehicle_id', $vehicleId)->exists();
            if ($existingImages) {
                return response()->json([
                    'message' => 'Vehicle images already exist for this vehicle, kindly update the images from vehicle details page.',
                ], 409); // 409 Conflict
            }

            // Merge form data + files
            $data = array_merge($request->all(), $request->allFiles());

            // Add vehicle_unique_id to request data
            $data['unique_id'] = $vehicle->unique_id ?? 'VEH-' . now()->timestamp;

            // Call service method
            $result = $this->vehicleService->storeImagesNewVehicle($vehicleId, $data);

            return response()->json([
                'message' => 'Vehicle images uploaded successfully.',
                'data' => $result
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to upload vehicle images.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/dealerApi/v1/vehicles/{vehicle}",
     *     summary="Update Vehicle - Dealer",
     *     description="Update vehicle details (Car or Bike) for the logged-in dealer. Use multipart/form-data with _method=PUT.",
     *     operationId="updateVehicledetails",
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
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="_method", type="string", example="PUT"),
     *                 @OA\Property(property="cmp_id", type="integer", example=10),
     *                 @OA\Property(property="model_id", type="integer", example=101),
     *                 @OA\Property(property="variant_id", type="integer", example=202),
     *                 @OA\Property(property="fuel_type", type="integer", example=1),
     *                 @OA\Property(property="mileage", type="number", example=18.5),
     *                 @OA\Property(property="kms_driven", type="integer", example=25000),
     *                 @OA\Property(property="owner", type="integer", example=1),
     *                 @OA\Property(property="featured_status", type="integer", enum={1,2}, example=1),
     *                 @OA\Property(property="onsale_status", type="integer", enum={1,2}, example=1),
     *                 @OA\Property(property="manufacture_year", type="integer", example=2020),
     *                 @OA\Property(property="registration_year", type="integer", example=2021),
     *                 @OA\Property(property="registered_state_id", type="integer", example=14),
     *                 @OA\Property(property="rto", type="string", example="539"),
     *                 @OA\Property(property="insurance_type", type="integer", enum={1,2}, example=1),
     *                 @OA\Property(property="insurance_validity", type="string", format="date", example="2026-05-31"),
     *                 @OA\Property(property="accidental_status", type="integer", enum={1,2}, example=2),
     *                 @OA\Property(property="flooded_status", type="integer", enum={1,2}, example=2),
     *                 @OA\Property(property="last_service_kms", type="integer", example=24000),
     *                 @OA\Property(property="is_active", type="integer", enum={1,2,3,4}, example=1),
     *                 @OA\Property(property="thumbnail_url", type="string", format="binary", description="Vehicle thumbnail image"),
     *                 @OA\Property(property="onsale_percentage", type="number", example=10.5),
     *                 @OA\Property(property="search_keywords", type="string", example="SUV, black, 2020"),
     *                 @OA\Property(property="last_service_date", type="string", format="date", example="2025-12-10"),
     *                 @OA\Property(property="regular_price", type="number", example=550000),
     *                 @OA\Property(property="selling_price", type="number", example=520000),
     *                 @OA\Property(property="pricing_type", type="integer", enum={1,2}, example=2),
     *                 @OA\Property(property="emi_option", type="integer", enum={1,2}, example=1),
     *                 @OA\Property(property="avg_interest_rate", type="number", example=10.5),
     *                 @OA\Property(property="tenure_months", type="integer", example=36),
     *                 @OA\Property(property="reservation_amt", type="integer", example=5000),
     *                 @OA\Property(property="soldReason", type="string", example="Customer request"),
     *
     *                 @OA\Property(property="transmission_id", type="integer", example=2),
     *                 @OA\Property(property="body_type", type="integer", example=3),
     *                 @OA\Property(property="car_no_of_airbags", type="integer", example=2),
     *                 @OA\Property(property="car_central_locking", type="integer", enum={1,2,3}),
     *                 @OA\Property(property="car_seat_upholstery", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_sunroof", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_integrated_music_system", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_rear_ac", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_outside_rear_view_mirrors", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_power_windows", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_engine_start_stop", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_headlamps", type="integer", enum={1,2}),
     *                 @OA\Property(property="car_power_steering", type="integer", enum={1,2}),
     *
     *                 @OA\Property(property="bike_headlight_type", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_odometer", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_drl", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_mobile_connectivity", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_gps_navigation", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_usb_charging_port", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_low_battery_indicator", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_under_seat_storage", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_speedometer", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_stand_alarm", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_low_fuel_indicator", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_low_oil_indicator", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_start_type", type="integer", enum={1,2,3}),
     *                 @OA\Property(property="bike_kill_switch", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_break_light", type="integer", enum={1,2}),
     *                 @OA\Property(property="bike_turn_signal_indicator", type="integer", enum={1,2})
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Vehicle")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */

    public function updateVehicledetails(UpdateVehicleRequest $request, Vehicles $vehicle) {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized. Please login.'], 401);
            }

            // Check if the vehicle belongs to one of the dealer's branch
            $dealerBranchIds = $user->branch()->pluck('id')->toArray();

            if (!in_array($vehicle->branch_id, $dealerBranchIds)) {
                return response()->json([
                    'message' => 'Unauthorized. You do not have permission to update this vehicle.'
                ], 403);
            }

            $data = $request->validated();

            // Handle new thumbnail upload
            if ($request->hasFile('thumbnail_url')) {
                $file = $request->file('thumbnail_url');

                if ($file->isValid()) {
                    $data['thumbnail_url'] = $file->store('vehicle_thumbnails/' . $vehicle->unique_id, 'public');
                } else {
                    return response()->json([
                        'message' => 'Uploaded thumbnail file is not valid.',
                    ], 422);
                }
            }

            // Perform update via service
            $updatedVehicle = $this->vehicleService->updateVehicledetails($vehicle, $data);

            return response()->json([
                'message' => 'Vehicle updated successfully.',
                'data' => new VehicleResource($updatedVehicle),
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update vehicle.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/dealerApi/v1/vehicles/{vehicle}/update-images",
     *     operationId="updateVehicleImages",
     *     summary="Update vehicle images",
     *     description="Allows an authenticated dealer to update a vehicle's detailed information and upload multiple images.",
     *     tags={"Dealer: Vehicles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="vehicle",
     *         in="path",
     *         required=true,
     *         description="ID of the vehicle to update",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=false,
     *         description="Vehicle image and detail data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="exterior_main_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_right_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_left_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_tank_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_handlebar_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_headlight_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_tail_light_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_speedometer_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_exhaust_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_seat_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_roof_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_bonetopen_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_main_engine_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_diagnoal_right_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_diagnoal_right_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_diagnoal_left_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_diagnoal_left_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_rear_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_right_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_right_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_left_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_left_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_wheel_spare_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_tyrethread_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_tyrethread_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_tyrethread_right_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_tyrethread_right_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_tyrethread_left_back_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_tyrethread_left_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_underbody_front_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_underbody_rear_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_underbody_right_img", type="string", format="binary"),
     *                 @OA\Property(property="exterior_underbody_left_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_dashboard_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_infotainment_system_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_steering_wheel_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_odometer_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_gear_lever_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_pedals_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_front_cabin_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_mid_cabin_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_rear_cabin_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_driver_side_door_panel_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_driver_side_adjustment_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_boot_inside_img", type="string", format="binary"),
     *                 @OA\Property(property="interior_boot_door_open_img", type="string", format="binary"),
     *                 @OA\Property(property="others_keys_img", type="string", format="binary")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vehicle updated successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized. Please login.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized. You do not have permission to update this vehicle.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation error."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to update vehicle.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update vehicle."),
     *             @OA\Property(property="error", type="string", example="Unexpected error occurred.")
     *         )
     *     )
     * )
     */

    public function updateVehicleImages(Request $request, Vehicles $vehicle) {
        try {
            if (!$vehicle) {
                return response()->json(['message' => 'Vehicle not found.'], 404);
            }

            $data = array_merge($request->all(), $request->allFiles());
            $data['unique_id'] = $vehicle->unique_id ?? 'VEH-' . now()->timestamp;

            $result = $this->vehicleService->updateVehicleImages($vehicle->id, $data);

            return response()->json([
                'message' => 'Vehicle images updated successfully.',
                'data' => $result
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update vehicle images.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/dealerApi/v1/vehicles/{vehicle}",
     *     summary="Get Vehicle Details",
     *     description="Fetches details of a specific vehicle by ID",
     *     operationId="getVehicleDetails",
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
     *         description="Vehicle details fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Vehicle fetched successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Vehicle not found or access denied"),
     * )
     */
    public function show($id) {
        $user = AUTH::user(); // minor fix: use lowercase auth() helper

        try {
            $vehicle = $this->vehicleService->getVehicleById($id, $user);

            return response()->json([
                'status' => true,
                'message' => 'Vehicle fetched successfully.',
                'data' => new VehicleResource($vehicle)
            ]);
        } catch (ModelNotFoundException $e) { // precise exception
            return response()->json([
                'status' => false,
                'message' => $e->getMessage() ?: 'Vehicle not found or access denied.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/dealerApi/v1/vehicles/{vehicle}",
     *     summary="Delete Vehicle - Remove From Listing",
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

    public function destroy($id) {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized. Please login.'], 401);
            }

            // Retrieve vehicle or throw 404
            $vehicle = Vehicles::findOrFail($id);

            // Check authorization
            $dealerBranchIds = $user->branch()->pluck('id')->toArray();
            if (!in_array($vehicle->branch_id, $dealerBranchIds)) {
                return response()->json([
                    'message' => 'Unauthorized. You do not have permission to delete this vehicle. / It does not belong to your branch.'
                ], 403);
            }

            DB::beginTransaction();
            $this->vehicleService->deleteVehicle($vehicle);
            DB::commit();

            return response()->json(['message' => 'Vehicle removed successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Vehicle not found.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to remove vehicle.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
