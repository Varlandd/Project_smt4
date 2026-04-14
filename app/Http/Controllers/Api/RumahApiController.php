<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RumahApiController extends Controller
{
    /**
     * List rumah (paginated)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $rumah = Rumah::latest()
            ->paginate($perPage);

        // Tambahkan is_favorit untuk setiap rumah
        $user = Auth::user();
        if ($user) {
            $userId = (string) $user->_id;
            $rumah->getCollection()->transform(function ($item) use ($userId) {
                $item->is_favorit = in_array($userId, $item->favorited_user_ids ?? []);
                return $item;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $rumah,
        ]);
    }

    /**
     * Detail rumah
     */
    public function show($id)
    {
        $rumah = Rumah::find($id);

        if (!$rumah) {
            return response()->json([
                'success' => false,
                'message' => 'Rumah tidak ditemukan',
            ], 404);
        }

        // Check is_favorit
        $user = Auth::user();
        if ($user) {
            $rumah->is_favorit = in_array((string) $user->_id, $rumah->favorited_user_ids ?? []);
        }

        return response()->json([
            'success' => true,
            'data' => $rumah,
        ]);
    }

    /**
     * Search rumah by criteria
     */
    public function search(Request $request)
    {
        $query = Rumah::query();

        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'regex', new \MongoDB\BSON\Regex($request->lokasi, 'i'));
        }

        if ($request->filled('budget_min')) {
            $query->where('harga', '>=', (int) $request->budget_min);
        }

        if ($request->filled('budget_max')) {
            $query->where('harga', '<=', (int) $request->budget_max);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('fasilitas') && is_array($request->fasilitas)) {
            // Filter berdasarkan nama fasilitas yang tersimpan di embedded array
            $query->where('fasilitas', 'all', $request->fasilitas);
        }

        $rumah = $query->latest()->paginate($request->get('per_page', 10));

        // Tambahkan is_favorit
        $user = Auth::user();
        if ($user) {
            $userId = (string) $user->_id;
            $rumah->getCollection()->transform(function ($item) use ($userId) {
                $item->is_favorit = in_array($userId, $item->favorited_user_ids ?? []);
                return $item;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $rumah,
        ]);
    }
}
