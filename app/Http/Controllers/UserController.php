<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use App\Models\UsersCredentials;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;


/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="User model schema",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="contact_no", type="string", example="1234567890")
 * )
 */

class UserController extends ApiController {

    /**
     * @OA\Post(
     *     path="/api/dealerApi/v1/dealerRegister",
     *     summary="Dealer Registration",
     *     description="Registers a new user and stores their credentials separately.",
     *     tags={"Dealer: Registration/Login"},
     *     operationId="dealerRegister",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "contact_no", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="contact_no", type="string", example="9876543210"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="SecurePass123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="SecurePass123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *                 @OA\Property(property="contact_no", type="string", example="9876543210")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Registration failed"),
     *             @OA\Property(property="error", type="string", example="Database error message")
     *         )
     *     )
     * )
     */
    /* Dealer Registration method */

    public function register(Request $request) {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_no' => 'required|numeric|digits_between:9,15|unique:users,contact_no',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction(); // Start transaction

        try {
            // Create user (without password)
            $user = User::create([
                'name' => $request->name,
                'user_code' => 'UserD' . time(),
                'contact_no' => $request->contact_no,
                'role_id' => '2', // Role ID for dealer
                'email' => $request->email,
            ]);

            // Store password in users_credentials table
            UsersCredentials::create([
                'user_id' => $user->id,
                'is_active' => '1',
                'password' => Hash::make($request->password), // Hash password
            ]);

            DB::commit(); // Commit transaction

            return $this->successResponse([
                'message' => 'User registered successfully',
                'user' => $user,
            ]);
        } catch (Exception $e) {
            DB::rollBack(); // Rollback changes if any error occurs

            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/dealerApi/v1/dealerLogin",
     *     summary="Dealer Login",
     *     tags={"Dealer: Registration/Login"},
     *     operationId="dealerLogin",
     *     description="Authenticate Dealer and return an access token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
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
     *                 @OA\Property(property="user", ref="#/components/schemas/User"),
     *                 @OA\Property(property="token", type="string", example="1|sdfk9dsf8dsfksdf89sdf7...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Something went wrong",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Something went wrong")
     *         )
     *     )
     * )
     */
    /* User Login method */
    public function login(Request $request) {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Find user by email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // Get password from users_credentials table
            $userCredentials = UsersCredentials::where('user_id', $user->id)->first();

            if (!$userCredentials || !Hash::check($request->password, $userCredentials->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // Check if user already has an active token
            $existingToken = $user->tokens()->first(); // Get first active token if exists

            if ($existingToken) {
                return response()->json([
                    'message' => 'Already logged in',
                    'token' => $existingToken->plainTextToken, // Return existing token
                    'user' => $user,
                ], 200);
            }

            // Generate a new token if not already logged in
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse(
                'Login successful',
                [
                    'token' => $token,
                    'user' => $user,
                ],
                200
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                'Something went wrong',
                500
            );
        }
    }

    // Logout method
    public function logout(Request $request) {
        $user = $request->user(); // or Auth::user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'Not authenticated'], 401);
    }

    // Password reset methods can be added here if needed
    // Additional methods for user management can be added here

    public function generatPasswordManually($userId, $newPassword) {
        try {

            $password = Hash::make($newPassword);

            return response()->json(
                [
                    'message' => 'Password updated successfully',
                    'password' => $password
                ]
            );
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating password', 'error' => $e->getMessage()], 500);
        }
    }
}
