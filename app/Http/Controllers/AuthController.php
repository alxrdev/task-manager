<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Login information invalid',
            ], 401);
        }

        $user = User::where('email', $validated['email'])->firstOrFail();
        $access_token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $access_token,
            'token_type' => 'Bearer',
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'lowercase', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()]
        ]);

        $validated['password'] = Hash::make($request->password);

        $user = User::create($validated);
        $access_token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $access_token,
            'token_type' => 'Bearer',
            'data' => $user,
        ], 201);
    }
}
