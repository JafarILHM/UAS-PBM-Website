<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|string|in:operator,admin',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user(); // ambil user dari request

        if (!$user) {
            return response()->json(['message' => 'Not authenticated.'], 401);
        }

        // pastikan user memiliki method currentAccessToken
        if (method_exists($user, 'currentAccessToken')) {
            $token = $user->currentAccessToken();
            if ($token) {
                $token->delete(); // hapus token
                return response()->json(['message' => 'Logged out successfully']);
            }
        }

        return response()->json(['message' => 'No token found or invalid request.'], 400);
    }

    public function user(Request $request)
    {
        if (Auth::check()) {
            return response()->json($request->user());
        }
        return response()->json(['message' => 'Not authenticated.'], 401);
    }
}