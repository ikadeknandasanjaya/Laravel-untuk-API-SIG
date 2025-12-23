<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Services\GisApiService;

class AuthController extends Controller
{
    protected GisApiService $apiService;

    public function __construct(GisApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    //Login form

    public function showLoginForm()
    {
        if($this->apiService->isAuthenticated()){
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    //Login Request
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $response = $this->apiService->login(
            $request->email,
            $request->password
        );

        // Jika request dari API (Vue.js), return JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            if (isset($response['meta']['token'])) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login berhasil!',
                    'redirect' => '/dashboard',
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => $response['meta']['message'] ?? 'Login gagal',
                'errors' => ['email' => [$response['meta']['message'] ?? 'Login gagal']],
            ], 422);
        }

        // Jika request biasa (Blade), redirect
        if (isset($response['meta']['token'])) {
            return redirect()->route('dashboard')
                ->with('success', 'Login berhasil!');
        }

        return back()
            ->withInput()
            ->withErrors(['email' => $response['meta']['message'] ?? 'Login gagal']);
    }

     /**
     * Show register form
     */
    public function showRegisterForm()
    {
        if ($this->apiService->isAuthenticated()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Panggil API register
        $response = $this->apiService->register(
            $request->name,
            $request->email,
            $request->password
        );

        // Jika request dari API (Vue.js), return JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            if (isset($response['meta']['status']) && $response['meta']['status'] === 'success') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Registrasi berhasil! Silakan login.',
                    'redirect' => '/login',
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => $response['meta']['message'] ?? 'Registrasi gagal',
                'errors' => ['email' => [$response['meta']['message'] ?? 'Registrasi gagal']],
            ], 422);
        }

        // Jika request biasa (Blade), redirect
        if (isset($response['meta']['status']) && $response['meta']['status'] === 'success') {
            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login.');
        }

        return back()
            ->withInput()
            ->withErrors(['email' => $response['meta']['message'] ?? 'Registrasi gagal']);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        $this->apiService->logout();
        
        // Jika request dari API (Vue.js), return JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Logout berhasil!',
                'redirect' => '/login',
            ]);
        }
        
        return redirect()->route('login')
            ->with('success', 'Logout berhasil!');
    }

}