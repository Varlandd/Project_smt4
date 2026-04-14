<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rumah;
use Illuminate\Http\Request;

class FavoritController extends Controller
{
    /**
     * List favorit user
     */
    public function index(Request $request)
    {
        $userId = (string) $request->user()->_id;

        // Cari rumah yang memiliki user_id di array favorited_user_ids
        $favorits = Rumah::where('favorited_user_ids', $userId)
            ->get();

        // Tandai semua sebagai favorit
        $favorits->transform(function ($item) {
            $item->is_favorit = true;
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $favorits,
        ]);
    }

    /**
     * Add rumah ke favorit
     */
    public function store(Request $request, $rumahId)
    {
        $rumah = Rumah::find($rumahId);
        if (!$rumah) {
            return response()->json([
                'success' => false,
                'message' => 'Rumah tidak ditemukan',
            ], 404);
        }

        $userId = (string) $request->user()->_id;
        $currentFavs = $rumah->favorited_user_ids ?? [];

        // Cek apakah sudah ada
        if (in_array($userId, $currentFavs)) {
            return response()->json([
                'success' => false,
                'message' => 'Rumah sudah ada di favorit',
            ], 400);
        }

        // Tambahkan user ke array favorited_user_ids
        $currentFavs[] = $userId;
        $rumah->favorited_user_ids = $currentFavs;
        $rumah->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambah favorit',
        ]);
    }

    /**
     * Remove rumah dari favorit
     */
    public function destroy(Request $request, $rumahId)
    {
        $rumah = Rumah::find($rumahId);
        if (!$rumah) {
            return response()->json([
                'success' => false,
                'message' => 'Rumah tidak ditemukan',
            ], 404);
        }

        $userId = (string) $request->user()->_id;
        $currentFavs = $rumah->favorited_user_ids ?? [];

        // Hapus user dari array
        $rumah->favorited_user_ids = array_values(array_diff($currentFavs, [$userId]));
        $rumah->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus favorit',
        ]);
    }
}
