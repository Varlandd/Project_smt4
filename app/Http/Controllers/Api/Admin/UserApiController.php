<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $user->update(['role' => $validated['role']]);

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diubah',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus'
        ]);
    }
}
