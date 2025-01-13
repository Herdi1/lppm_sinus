<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        JWTAuth::factory()->setTTL(1440);
        $token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password]);

        if ($user) {
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token,
            ], 201);
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns|exists:users',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        JWTAuth::factory()->setTTL(1440);
        if (!$token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password yang diinputkan tidak sesuai, coba lagi.'
            ], 401);
        }

        $user = auth()->guard('api')->user();

        return response()->json([
            'message' => 'Login berhasil.',
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        if ($removeToken) {
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil.',
            ]);
        }
    }

    //get user
    public function me()
    {
        $user = auth()->guard('api')->user();
        return response()->json([
            'user' => $user,
            'roles' => $user->getRoleNames(),
        ]);
    }

    //change password
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'message' => 'Password lama tidak sesuai.'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Password berhasil diganti.'
        ], 200);
    }

    //update profile
    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => 'sometimes|max:255',
            'email' => 'sometimes|email|unique:users',
            'nidn' => 'sometimes|max:255',
            'cluster' => 'sometimes|max:255',
            'institution' => 'sometimes|max:255',
            'study_program' => 'sometimes|max:255',
            'education_level' => 'sometimes|max:255',
            'position' => 'sometimes|max:255',
            'address' => 'sometimes|max:255',
            'place_of_birth' => 'sometimes|max:255',
            'date_of_birth' => 'sometimes|date',
            'nik' => 'sometimes|max:255',
            'phone' => 'sometimes|max:255',
            'website' => 'sometimes|max:255'
        ]);

        $user = $request->user();

        $user->update($data);

        return response()->json([
            'message' => 'Profile User berhasil diperbarui.',
            'user' => $user
        ]);
    }
}
