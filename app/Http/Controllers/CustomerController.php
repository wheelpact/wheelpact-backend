<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TestDriveRequest;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *     title="Wheelpact API",
 *     version="1.0.0",
 *     description="API documentation for Wheelpact backend",
 *     @OA\Contact(
 *         email="dev@wheelpact.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class CustomerController extends ApiController {

    /**
     * @OA\Post(
     *     path="/api/customerApi/v1/customerRegister",
     *     summary="Register a new customer",
     *     tags={"Customers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","contact_no"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="contact_no", type="string", example="1234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Customer registered successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="contact_no", type="string", example="1234567890")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Customer not registered, please try again",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Customer not registered, please try again")
     *         )
     *     )
     * )
     */
    public function store(StoreCustomerRequest $request) {

        $request->validated();

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_no' => $request->contact_no
        ]);

        if ($customer) {
            return $this->successResponse('Customer registered successfully', $customer, 201);
        } else {
            return $this->errorResponse('Customer not registered, please try again', 500);
        }
    }

    // Display the specified resource
    public function show(Customer $customer) {
        return $customer;
    }

    // Show the form for editing the specified resource (for web)
    public function edit(Customer $customer) {
        //
    }

    // Update the specified resource in storage
    public function update(StoreCustomerRequest $request, Customer $customer) {
        $customer->update($request->all());
        return response()->json($customer, 200);
    }

    // Remove the specified resource from storage
    public function destroy(Customer $customer) {
        $customer->delete();
        return response()->json(null, 204);
    }

    // 👤 Get Authenticated User
    public function customer(StoreCustomerRequest $request) {
        return response()->json([
            'customer' => $request->customer()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/customerApi/v1/customergenerateOtp",
     *     summary="Generate OTP for Customer Login",
     *     tags={"Customers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP generated and sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="OTP generated and sent successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="otp", type="integer", example=123456)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Customer not found")
     *         )
     *     )
     * )
     */

    public function generateOtp(Request $request) {

        // Validate customer identifier (e.g., contact_no or email)
        $request->validate([
            'email' => 'required|email'
        ]);

        // 1. Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // 2. Store OTP in the database (assumes 'otp' column exists)
        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            return $this->errorResponse('Customer not found', 404);
        }

        // Save OTP and timestamp (optional expiry handling)
        $customer->otp = $otp;
        $customer->otp_status = 1; // 1 = Active, 0 = Inactive
        $customer->save();

        // 3. Optionally, send OTP via SMS/email here...

        // 4. Return success response (omit 'otp' in production)
        return $this->successResponse('OTP generated and sent successfully', [
            'otp' => $otp,
            'email' => $request->email
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/customerApi/v1/customervalidateOtpLogin",
     *     summary="Validate OTP for Customer Login",
     *     tags={"Customers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "otp"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="otp", type="integer", example=123456)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="customer", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="contact_no", type="string", example="1234567890")
     *                 ),
     *                 @OA\Property(property="token", type="string", example="1|sdfk9dsf8dsfksdf89sdf7...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid OTP or email",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Invalid OTP or email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 ),
     *                 @OA\Property(property="otp", type="array",
     *                     @OA\Items(type="string", example="The otp field must be 6 digits.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    /* customer login with otp */
    public function validateOtpLogin(Request $request) {
        // Step 1: Validate input
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'otp'   => ['required', 'digits:6'],
        ]);

        // Step 2: Attempt to find customer with valid OTP and otp_status = 1
        $customer = Customer::where('email', $validated['email'])
            ->where('otp', $validated['otp'])
            ->where('otp_status', 1)
            ->first();

        if (!$customer) {
            // No customer found with matching OTP and email
            return $this->errorResponse('Invalid OTP or email', 401);
        }

        try {
            // Step 3: Mark OTP as used inside a transaction to ensure consistency
            DB::beginTransaction();

            //update customer otp status & otp as null
            $customer->otp = NULL;
            $customer->otp_status = 0;
            $customer->save();

            // Generate token using Sanctum
            $token = $customer->createToken('customer_token')->plainTextToken;

            DB::commit();

            // Return success response with token
            return $this->successResponse('Login successful', [
                'customer' => $customer,
                'token' => $token
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack(); // Revert OTP status if anything fails

            // Log the error for debugging
            Log::error('OTP Login Error: ' . $e->getMessage());

            return $this->errorResponse('An error occurred while processing your request.', 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/customerApi/v1/customerTestDriveRequest",
     *     summary="Get Test Drive Requests for Logged-in Customer",
     *     tags={"Customers"},
     *     security={{"bearerAuth": {}}}, 
     *     description="Returns the list of test drive requests for the authenticated customer. Requires a valid Bearer token (Sanctum).",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Test drive list retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Test drive list retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="customer_id", type="integer", example=5),
     *                     @OA\Property(property="customer_name", type="string", example="John Doe"),
     *                     @OA\Property(property="customer_phone", type="string", example="+1234567890"),
     *                     @OA\Property(property="customer_email", type="string", example="john@example.com"),
     *                     @OA\Property(property="branch_id", type="integer", example=2),
     *                     @OA\Property(property="vehicle_id", type="integer", example=3),
     *                     @OA\Property(property="dateOfVisit", type="string", format="date", example="2025-03-25"),
     *                     @OA\Property(property="timeOfVisit", type="string", example="1"),
     *                     @OA\Property(property="comments", type="string", example="Looking forward to the test drive."),
     *                     @OA\Property(property="license_file_path", type="string", example="uploads/licenses/license123.jpg"),
     *                     @OA\Property(property="status", type="string", example="pending"),
     *                     @OA\Property(property="is_active", type="string", example="1"),
     *                     @OA\Property(property="reason_selected", type="string", example="Convenient time"),
     *                     @OA\Property(property="dealer_comments", type="string", example="Confirmed"),
     *                     @OA\Property(property="update_by", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T10:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T10:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated. Please log in to continue.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated. Please log in to continue.")
     *         )
     *     )
     * )
     */

    // Get test drive list data for the logged-in customer
    public function getTestDriveRequestList(Request $request) {
        // Authenticated customer from token (via Sanctum)
        $customer = $request->user();  // Same as Auth::user()

        if (!$customer) {
            return $this->errorResponse('Unauthenticated. Please log in to continue.', 401);
        }

        // Get test drive list associated with the authenticated customer
        $testDriveList = TestDriveRequest::where('customer_id', $customer->id)->get();

        return $this->successResponse('Test drive list retrieved successfully', $testDriveList, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/customerApi/v1/customerTestDriveRequest",
     *     summary="Submit a Test Drive Request",
     *     tags={"Customers"},
     *     security={{"bearerAuth": {}}},
     *     description="Authenticated customer submits a test drive request with required customer details and license file.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={
     *                     "customer_id",
     *                     "customer_name",
     *                     "customer_phone",
     *                     "branch_id", 
     *                     "vehicle_id", 
     *                     "dateOfVisit", 
     *                     "timeOfVisit", 
     *                     "comments",
     *                     "license_file_path"
     *                 },
     *                 @OA\Property(property="customer_id", type="integer", example=2),
     *                 @OA\Property(property="customer_name", type="string", example="John Doe"),
     *                 @OA\Property(property="customer_phone", type="string", example="1234567890"),
     *                 @OA\Property(property="branch_id", type="integer", example=2),
     *                 @OA\Property(property="vehicle_id", type="integer", example=3),
     *                 @OA\Property(property="dateOfVisit", type="string", format="date", example="2025-03-25"),
     *                 @OA\Property(property="timeOfVisit", type="string", example="1"),
     *                 @OA\Property(property="comments", type="string", example="Looking forward to the test drive."),
     *                 @OA\Property(property="license_file_path", type="string", format="binary", description="Upload license file")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Test drive request submitted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Test drive request submitted successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="customer_id", type="integer", example=5),
     *                 @OA\Property(property="customer_name", type="string", example="John Doe"),
     *                 @OA\Property(property="customer_phone", type="string", example="+1234567890"),
     *                 @OA\Property(property="customer_email", type="string", example="john@example.com"),
     *                 @OA\Property(property="branch_id", type="integer", example=2),
     *                 @OA\Property(property="vehicle_id", type="integer", example=3),
     *                 @OA\Property(property="dateOfVisit", type="string", format="date", example="2025-03-25"),
     *                 @OA\Property(property="timeOfVisit", type="string", example="1"),
     *                 @OA\Property(property="license_file_path", type="string", example="uploads/licenses/license123.jpg"),
     *                 @OA\Property(property="status", type="string", example="Pending"),
     *                 @OA\Property(property="is_active", type="integer", example=1),
     *                 @OA\Property(property="update_by", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T10:00:00Z")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated. Please log in to continue.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated. Please log in to continue.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="branch_id", type="array", @OA\Items(type="string", example="The branch_id field is required.")),
     *                 @OA\Property(property="license_file_path", type="array", @OA\Items(type="string", example="The license_file_path field is required."))
     *             )
     *         )
     *     )
     * )
     */

    // Store test drive request for the logged-in customer
    public function storeTestDriveRequest(Request $request) {
        // Authenticated customer from token (via Sanctum)
        $customer = $request->user();

        if (!$customer) {
            return $this->errorResponse('Unauthenticated. Please log in to continue.', 401);
        }

        // Validate request data
        $validated = $request->validate([
            'branch_id' => 'required|integer',
            'vehicle_id' => 'required|integer',
            'dateOfVisit' => 'required|date',
            'timeOfVisit' => 'required',
            'comments' => 'nullable|string',
            'license_file_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',  // Validate image
        ]);

        // Handle license image upload
        $licensePath = null;
        if ($request->hasFile('license_file_path')) {
            $file = $request->file('license_file_path');
            $licensePath = $file->store('customer/test_drive_request_license', 'public');  // Stored in storage/app/public/test_drive_request_license
        }

        // Create test drive request
        $testDriveRequest = TestDriveRequest::create([
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->contact_no,
            'customer_email' => $customer->email,
            'branch_id' => $validated['branch_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'dateOfVisit' => $validated['dateOfVisit'],
            'timeOfVisit' => $validated['timeOfVisit'],
            'comments' => $validated['comments'] ?? null,
            'license_file_path' => $licensePath,
            'status' => 'Pending',
            'is_active' => 1,
            'update_by' => null
        ]);

        return $this->successResponse('Test drive request submitted successfully', $testDriveRequest, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/customerApi/v1/logout",
     *     summary="Logout Customer (invalidate current token)",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Not authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not authenticated")
     *         )
     *     )
     * )
     */

    // 🚪 Logout method
    public function logout(Request $request) {
        $user = $request->user(); // or Auth::user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'Not authenticated'], 401);
    }
}
