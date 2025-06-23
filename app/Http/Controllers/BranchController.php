<?php

namespace App\Http\Controllers;

use Exception;

use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\BranchStoreRequest;
use App\Http\Requests\Branch\BranchUpdateRequest;
use App\Services\BranchService;
use App\Resources\BranchResource;
use App\Models\Branch;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller {
    protected BranchService $service;

    public function __construct(BranchService $service) {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/dealerApi/v1/branches",
     *     summary="List all branches of the authenticated dealer",
     *     description="Returns a paginated list of branches created by the logged-in dealer.",
     *     operationId="getDealerBranches",
     *     tags={"Dealer: Branches"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of branches",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Branch Name"),
     *                     @OA\Property(property="branch_type", type="integer", example=1),
     *                     @OA\Property(property="contact_number", type="string", example="9876543210"),
     *                     @OA\Property(property="email", type="string", example="branch@example.com"),
     *                     @OA\Property(property="thumbnail", type="string", example="thumb.jpg"),
     *                     @OA\Property(property="logo", type="string", example="logo.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */

    public function index(Request $request): JsonResponse {
        try {
            $dealer = Auth::user();

            if (!$dealer) {
                return response()->json(['message' => 'Unauthorized. Please login.'], 401);
            }

            $branches = $this->service->list($dealer);

            return response()->json([
                'success' => true,
                'data' => BranchResource::collection($branches),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching branches.',
                'error' => $e->getMessage(), // optional: remove in production
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/dealerApi/v1/branches",
     *     summary="Create a new branch",
     *     description="Creates a new branch with optional banners and multiple deliverable images.",
     *     operationId="createBranch",
     *     tags={"Dealer: Branches"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "branch_type", "branch_supported_vehicle_type", "branch_services", "country_id", "state_id", "city_id", "address", "contact_number", "whatsapp_no", "email", "short_description", "map_latitude", "map_longitude", "map_city", "map_district", "map_state", "branch_logo", "branch_thumbnail"},
     *                 @OA\Property(property="name", type="string", description="Name of branch"),
     *                 @OA\Property(
     *                  property="branch_type",
     *                  type="integer",
     *                  description="Type of branch (1=Main Branch, 2=Sub Branch)",
     *                  enum={1, 2},
     *                  example=1
     *                 ),
     *                 @OA\Property(
     *                  property="branch_supported_vehicle_type",
     *                  type="integer",
     *                  description="Supported vehicle types (1=Car, 2=Bike, 3=Both)",
     *                  enum={1, 2, 3},
     *                  example=3
     *                 ),
     *                 @OA\Property(
     *                  property="branch_services",
     *                  type="array",
     *                  description="Services offered by the branch",
     *                  example={"Oil Change", "Tire Replacement", "Brake Inspection"},
     *                  @OA\Items(type="string")
     *                 ),
     *                 @OA\Property(property="country_id", type="integer", example="101", description="ID of the country where the branch is located"),
     *                 @OA\Property(property="state_id", type="integer", example="201", description="ID of the state where the branch is located"),
     *                 @OA\Property(property="city_id", type="integer", example="301", description="ID of the city where the branch is located"),
     *                 @OA\Property(property="address", type="string", description="Full address of the branch", example="123 Main St, City, State, 12345"),
     *                 @OA\Property(property="contact_number", type="string", description="Contact number of branch", example="9876543210"),
     *                 @OA\Property(property="whatsapp_no", type="string", description="WhatsApp number of branch", example="9876543210"),
     *                 @OA\Property(property="email", type="string", format="email", example="jhon@example.com", description="Email address of the branch"),
     *                 @OA\Property(property="short_description", type="string"),
     *                 @OA\Property(property="branch_map", type="string"),
     *                 @OA\Property(property="map_latitude", type="string", example="12.9715987", description="Latitude for branch location on map"),
     *                 @OA\Property(property="map_longitude", type="string", example="77.594566", description="Longitude for branch location on map"),
     *                 @OA\Property(property="map_city", type="string", example="Mumbai", description="City for branch location on map"),
     *                 @OA\Property(property="map_district", type="string", example="Mumbai District", description="District for branch location on map"),
     *                 @OA\Property(property="map_state", type="string", example="Maharashtra", description="State for branch location on map"),
     *                 @OA\Property(property="branch_logo", type="string", format="binary"),
     *                 @OA\Property(property="branch_thumbnail", type="string", format="binary", description="Branch thumbnail image"),                
     *                 @OA\Property(property="branch_banner1", type="string", format="binary"),
     *                 @OA\Property(property="branch_banner2", type="string", format="binary"),
     *                 @OA\Property(property="branch_banner3", type="string", format="binary"),
     *                 
     *                 @OA\Property(
     *                     property="deliverables_img_name[]",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Branch created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Branch created successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */

    public function store(BranchStoreRequest $request): JsonResponse {

        // Ensure execution stops here
        $validatedData = $request->validated();

        try {
            $branch = $this->service->store($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'Branch created successfully.',
                'data' => new BranchResource($branch),
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/dealerApi/v1/branches/{id}",
     *     summary="Update an existing branch",
     *     description="Updates an existing branch with optional logo, banners, and deliverables using POST with _method=PUT (multipart form).",
     *     operationId="updateBranch",
     *     tags={"Dealer: Branches"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the branch to update",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     example="PUT",
     *                     description="Override method for Laravel PUT request"
     *                 ),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="branch_type", type="integer", enum={1,2}),
     *                 @OA\Property(property="branch_supported_vehicle_type", type="integer", enum={1,2,3}),
     *                 @OA\Property(
     *                     property="branch_services",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"Oil Change", "Battery Service"}
     *                 ),
     *                 @OA\Property(property="country_id", type="integer", example=101),
     *                 @OA\Property(property="state_id", type="integer", example=201),
     *                 @OA\Property(property="city_id", type="integer", example=301),
     *                 @OA\Property(property="address", type="string", example="123 Main St, City, State, 12345"),
     *                 @OA\Property(property="contact_number", type="string", example="9876543210"),
     *                 @OA\Property(property="whatsapp_no", type="string", example="9876543210"),
     *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                 @OA\Property(property="short_description", type="string"),
     *                 @OA\Property(property="branch_map", type="string"),
     *                 @OA\Property(property="map_latitude", type="string", example="12.9715987"),
     *                 @OA\Property(property="map_longitude", type="string", example="77.594566"),
     *                 @OA\Property(property="map_city", type="string", example="Mumbai"),
     *                 @OA\Property(property="map_district", type="string", example="Mumbai District"),
     *                 @OA\Property(property="map_state", type="string", example="Maharashtra"),
     *                 @OA\Property(property="branch_logo", type="string", format="binary"),
     *                 @OA\Property(property="branch_thumbnail", type="string", format="binary", description="Branch thumbnail image"),                
     *                 @OA\Property(property="branch_banner1", type="string", format="binary"),
     *                 @OA\Property(property="branch_banner2", type="string", format="binary"),
     *                 @OA\Property(property="branch_banner3", type="string", format="binary"),
     * 
     *                 @OA\Property(
     *                     property="deliverables_img_name[]",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Branch updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Branch updated successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error or update failure",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Branch update failed.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */

    public function update(BranchUpdateRequest $request, int $id): JsonResponse {
        try {
            $dealer = AUTH::user();

            $branch = Branch::where('id', $id)
                ->where('dealer_id', $dealer->id)
                ->first();

            if (!$branch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found or unauthorized.',
                ], 404);
            }

            $updatedBranch = $this->service->update($branch->id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Branch updated successfully.',
                'data' => new BranchResource($updatedBranch),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Branch update failed.',
                'error' => $e->getMessage(), // Remove in production
            ], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/dealerApi/v1/branches/{id}",
     *     summary="Get branch details by ID",
     *     description="Returns the branch details if it belongs to the authenticated dealer.",
     *     operationId="getDealerBranchById",
     *     tags={"Dealer: Branches"},
     *     security={{"bearerAuth":{}}},
     * 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the branch",
     *         @OA\Schema(type="integer", example=123)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Branch details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Branch details fetched successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/BranchResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Branch not found or unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Branch not found or unauthorized.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */

    public function show(int $id): JsonResponse {
        try {

            $dealer = AUTH::user();

            $branch = $this->service->getByIdAndDealer($id, $dealer->id);

            if (!$branch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found or unauthorized.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Branch details fetched successfully.',
                'data' => new BranchResource($branch),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch branch details.',
                'error' => $e->getMessage(), // Remove in production
            ], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/dealerApi/v1/branches/{id}",
     *     summary="Soft delete a branch",
     *     description="Soft deletes a branch if it belongs to the authenticated dealer.",
     *     operationId="deleteBranch",
     *     tags={"Dealer: Branches"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the branch to delete",
     *         @OA\Schema(type="integer",example=123)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Branch deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Branch deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Branch not found or unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Branch not found or unauthorized.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse {

        try {
            $dealer = Auth::user();

            $branch = $this->service->getByIdAndDealer($id, $dealer->id);

            if (!$branch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found or unauthorized.',
                ], 404);
            }

            // Ensure the user is authenticated
            $deleted = $this->service->delete($id, $dealer->id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found or unauthorized.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Branch deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Branch deletion failed.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
