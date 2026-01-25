<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register
     */
    public function register(RegisterRequest $request)
    {
        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Register Successfully',
            'data' => [
                'token' => $token,
                'user' => new UserResource($user),
            ],
        ]);
    }

    /**
     * Login
     */
    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        // Create Token
        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Successfully',
            'data' => [
                'token' => $token,
                'user' => new UserResource(Auth::user()),
            ],
        ]);
    }
}