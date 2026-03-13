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

        $rumah = Rumah::with('fasilitas')
            ->latest()
            ->paginate($perPage);

        // Tambahkan is_favorit untuk setiap rumah
        $user = Auth::user();
        if ($user) {
            $favoritIds = $user->favorits()->pluck('rumah_id')->toArray();
            $rumah->getCollection()->transform(function ($item) use ($favoritIds) {
                $item->is_favorit = in_array($item->id, $favoritIds);
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
        $rumah = Rumah::with('fasilitas')->find($id);

        if (!$rumah) {
            return response()->json([
                'success' => false,
                'message' => 'Rumah tidak ditemukan',
            ], 404);
        }

        // Check is_favorit
        $user = Auth::user();
        if ($user) {
            $rumah->is_favorit = $user->favorits()->where('rumah_id', $id)->exists();
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
        $query = Rumah::with('fasilitas');

        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', '%' . $request->lokasi . '%');
        }

        if ($request->filled('budget_min')) {
            $query->where('harga', '>=', $request->budget_min);
        }

        if ($request->filled('budget_max')) {
            $query->where('harga', '<=', $request->budget_max);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('fasilitas') && is_array($request->fasilitas)) {
            $fasilitasIds = $request->fasilitas;
            $query->whereHas('fasilitas', function ($q) use ($fasilitasIds) {
                $q->whereIn('fasilitas.id', $fasilitasIds);
            });
        }

        $rumah = $query->latest()->paginate($request->get('per_page', 10));

        // Tambahkan is_favorit
        $user = Auth::user();
        if ($user) {
            $favoritIds = $user->favorits()->pluck('rumah_id')->toArray();
            $rumah->getCollection()->transform(function ($item) use ($favoritIds) {
                $item->is_favorit = in_array($item->id, $favoritIds);
                return $item;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $rumah,
        ]);
    }
}
