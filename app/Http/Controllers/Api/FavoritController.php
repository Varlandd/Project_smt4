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
        $favorits = $request->user()->favorits()->with('fasilitas')->get();

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

        // Cek apakah sudah ada
        if ($request->user()->favorits()->where('rumah_id', $rumahId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Rumah sudah ada di favorit',
            ], 400);
        }

        $request->user()->favorits()->attach($rumahId);

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
        $request->user()->favorits()->detach($rumahId);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus favorit',
        ]);
    }
}
