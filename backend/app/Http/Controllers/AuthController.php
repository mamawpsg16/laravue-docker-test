<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    // Register new user
    public function register(RegisterFormRequest $request)
    {
        $data = $request->validated();
        $data['password']  = Hash::make($data['password']);
        
        $user = User::create($data);

            event(new Registered($user));
            
            // Automatically log in the user after registration
            Auth::login($user);

            // Return response with user data
            return response()->json([
                'message' => 'Registration successful!',
                'user' => $user,
            ], 201);
        }

    // Login existing user
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt login
        if (Auth::attempt($credentials)) {
            // Fetch the logged-in user
            $user = Auth::user();

            return response()->json([
                'message' => 'Login successful!',
                'user' => $user,
            ]);
        }

        return response()->json([
            'message' => 'Invalid login credentials',
        ], 401);
    }

    // Get logged-in user
    public function user(Request $request)
    {
        // Automatically handled by Sanctum
        return response()->json($request->user());
    }

    // Logout the user
    public function logout(Request $request)
    {
        // Invalidate the user's session (Sanctum session-based)
        Auth::logout();

        // Also invalidate the Sanctum session cookie
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

}
