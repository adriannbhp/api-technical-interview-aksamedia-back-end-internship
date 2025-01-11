<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DTOs\Request\LoginRequest;
use App\Http\Controllers\DTOs\Request\RegisterRequest;
use App\Http\Controllers\DTOs\Response\DefaultResponse;
use App\Http\Controllers\DTOs\Response\GlobalResponse;
use App\Http\Controllers\DTOs\Response\LogoutResponse;
use App\Http\Exceptions\AuthException; // Import the custom exception
use App\Constants\ResponseMessages; // Import the ResponseMessages class
use App\Constants\HttpStatusCodes; // Import the HttpStatusCodes class
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->only('username', 'password');

        // Attempt to authenticate the user
        if (!$token = auth()->guard('api')->attempt($credentials)) {
            \Log::warning('Authentication failed for credentials:', $credentials);
            throw new AuthException(ResponseMessages::ERROR_INVALID_CREDENTIALS, []);
        }

        // Retrieve the authenticated user's ID from the token
        $userId = auth()->guard('api')->id();

        // Use query builder to fetch the full user data by UUID
        $user = \DB::table('users')->where('id', $userId)->first();

        // Log error if user is not found
        if (!$user) {
            return response()->json(new GlobalResponse(
                'error',
                'User not found after authentication.',
                []
            ), HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }

        // Build the response using DefaultResponse
        return response()->json(new GlobalResponse(
            'success',
            ResponseMessages::SUCCESS_LOGIN,
            [
                'token' => $token,
                'admin' => [
                    'id' => (string) $user->id, // Ensure ID is returned as a string
                    'name' => $user->name,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'email' => $user->email,
                ],
            ]
        ), HttpStatusCodes::OK);
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        // Check if the token was successfully invalidated
        if ($removeToken) {
            // Return success response
            return response()->json(new LogoutResponse(
                'success',
                ResponseMessages::SUCCESS_LOGOUT
            ), HttpStatusCodes::OK);
        }

        // Return error response
        return response()->json(new LogoutResponse(
            'error',
            ResponseMessages::ERROR_LOGOUT_FAILED
        ), HttpStatusCodes::INTERNAL_SERVER_ERROR);
    }


    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validated();

        $uuid = (string) Str::uuid(); // Generate UUID for the 'id' field

        // Insert user data into the database using query builder
        \DB::table('users')->insert([
            'id' => $uuid,
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => bcrypt($validatedData['password']),
            'email_verified_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Retrieve the newly created user using query builder
        $user = \DB::table('users')->where('id', $uuid)->first();

        // Use DefaultResponse for response
        return response()->json(new GlobalResponse(
            'success',
            ResponseMessages::SUCCESS_USER_CREATED,
            [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'email_verified_at' => $user->email_verified_at,
                ],
            ]
        ), HttpStatusCodes::CREATED);
    }
}
