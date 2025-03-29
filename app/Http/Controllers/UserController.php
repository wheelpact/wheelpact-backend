<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use App\Models\UsersCredentials;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;


class UserController extends ApiController {


    /**
     * Register function
     */

    public function register(Request $request) {


        $request->validate([
            'name' => 'required|string|max:255',
            'contact_no' => 'required|digits:10',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction(); // Start transaction

        try {
            // Create user (without password)
            $user = User::create([
                'name' => $request->name,
                'user_code' => 'UserD' . time(),
                'email' => $request->email,
            ]);

            // Store password in users_credentials table
            UsersCredentials::create([
                'user_id' => $user->id,
                'is_active' => 1,
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
     * Login function
     */
    public function login(Request $request) {

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
            return $this->errorResponse(
                'Invalid credentials',
                401
            );
        }

        // Generate a Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(
            [
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ]
        );
    }
}
