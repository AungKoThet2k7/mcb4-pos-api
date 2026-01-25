<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeNameRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangePhotoRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display user profile
     */
    public function show()
    {
        return response()->json([
            'message' => 'User profile retrieved successfully',
            'data' => new UserResource(Auth::user()),
        ]);
    }

    /**
     * Change user name
     */
    public function changeName(ChangeNameRequest $request)
    {
        $user = Auth::user();

        $user->update($request->validated());

        return response()->json([
            'message' => 'Name Changed successfully',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(
            [
                'message' => 'Password changed successfully',
            ]
        );
    }

    /**
     * Change Photo
     */
    public function changePhoto(ChangePhotoRequest $request)
    {
        $user = Auth::user();

        $user->update(['photo' => $request->photo]);

        return response()->json([
            'message' => 'Photo changed successfully',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Logout
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['data' => ['message' => 'Logged Out Successfully']], 200);
    }
}
