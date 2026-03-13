<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileApiController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if (!\Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini salah'
            ], 400);
        }

        $user->update([
            'password' => \Hash::make($validated['password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui',
        ]);
    }
}
