<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    /**
     * Register a new user (locally and remotely)
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            // 1. Register locally
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $localToken = $user->createToken('api-token')->plainTextToken;

            // 2. Try to register on remote API (Dosen's system)
            $remoteSuccess = false;
            
            \Log::info('Starting remote registration', [
                'email' => $validated['email'],
                'name' => $validated['name'],
            ]);
            
            try {
                \Log::info('Sending request to Dosen API');
                
                $remoteResponse = Http::timeout(10)->post('https://gisapis.manpits.xyz/api/register', [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                ]);

                \Log::info('Remote API response received', [
                    'status' => $remoteResponse->status(),
                    'body' => $remoteResponse->json(),
                ]);

                if ($remoteResponse->successful()) {
                    $remoteData = $remoteResponse->json();
                    // Check if registration was successful (dosen's API returns code 200)
                    if (isset($remoteData['meta']['code']) && $remoteData['meta']['code'] == 200) {
                        $remoteSuccess = true;
                        \Log::info('✅ Remote registration SUCCESSFUL for user ' . $validated['email']);
                    } else {
                        \Log::warning('❌ Remote registration FAILED - meta.code not 200', ['response' => $remoteData]);
                    }
                } else {
                    \Log::warning('❌ Remote registration FAILED - non-successful HTTP status', [
                        'status' => $remoteResponse->status(),
                    ]);
                }
            } catch (\Exception $remoteError) {
                // Remote registration failed, but local is successful
                // Log the error but don't fail the entire registration
                \Log::warning('❌ Remote registration EXCEPTION for user ' . $validated['email'] . ': ' . $remoteError->getMessage());
            }

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
                'access_token' => $localToken,
                'remote_success' => $remoteSuccess,
                'token_type' => 'Bearer',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Login user (locally and attempt remote login)
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Delete old tokens
        $user->tokens()->delete();

        $localToken = $user->createToken('api-token')->plainTextToken;

        // 2. Try to login to remote API (Dosen's system) to get JWT
        $remoteSuccess = false;
        $remoteToken = null;
        
        \Log::info('Starting remote login', [
            'email' => $validated['email'],
        ]);
        
        try {
            \Log::info('Sending login request to Dosen API');
            
            $remoteResponse = Http::timeout(10)->post('https://gisapis.manpits.xyz/api/login', [
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            \Log::info('Remote API login response received', [
                'status' => $remoteResponse->status(),
                'body' => $remoteResponse->json(),
            ]);

            if ($remoteResponse->successful()) {
                $remoteData = $remoteResponse->json();
                // Check if login was successful (dosen's API returns code 200)
                if (isset($remoteData['meta']['code']) && $remoteData['meta']['code'] == 200) {
                    $remoteToken = $remoteData['meta']['token'] ?? null;
                    if ($remoteToken) {
                        $remoteSuccess = true;
                        \Log::info('✅ Remote login SUCCESSFUL for user ' . $validated['email']);
                    }
                } else {
                    \Log::warning('❌ Remote login FAILED - meta.code not 200', ['response' => $remoteData]);
                }
            } else {
                \Log::warning('❌ Remote login FAILED - non-successful HTTP status', [
                    'status' => $remoteResponse->status(),
                ]);
            }
        } catch (\Exception $remoteError) {
            // Remote login failed, but local is successful
            \Log::warning('❌ Remote login EXCEPTION for user ' . $validated['email'] . ': ' . $remoteError->getMessage());
        }

        // Store remote token in session for backend use (e.g., GisApiService)
        if ($remoteToken) {
            Session::put('api_token', $remoteToken);
            \Log::info('Remote token stored in session');
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'access_token' => $localToken,
            'remote_token' => $remoteToken,
            'remote_success' => $remoteSuccess,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get current user
     */
    public function user(Request $request)
    {
        \Log::info('User endpoint called', [
            'user' => $request->user(),
            'headers' => $request->headers->all(),
        ]);
        return response()->json([
            'data' => $request->user(),
        ]);
    }
}
