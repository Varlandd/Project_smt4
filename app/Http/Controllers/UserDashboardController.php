<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rumah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class UserDashboardController extends Controller
{
    /**
     * Helper: check if a rumah is favorited by user.
     */
    private function isFavorited($rumah, string $userId): bool
    {
        $favs = $rumah->favorited_user_ids;
        if (!is_array($favs)) return false;
        foreach ($favs as $fav) {
            if ((string) $fav === $userId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Helper: get all favorited rumah for a user.
     */
    private function getFavoritedRumah(string $userId, $limit = null)
    {
        $all = $limit ? Rumah::all() : Rumah::all();
        $filtered = $all->filter(function ($rumah) use ($userId) {
            return $this->isFavorited($rumah, $userId);
        });

        $filtered->transform(function ($item) {
            $item->is_favorit = true;
            return $item;
        });

        return $limit ? $filtered->take($limit) : $filtered;
    }

    /**
     * User Dashboard — overview with stats, featured, and recent properties.
     */
    public function index()
    {
        $user = Auth::user();
        $userId = (string) $user->_id;

        // Stats
        $totalRumah = Rumah::count();

        // Count favorit using PHP filter
        $allRumah = Rumah::all();
        $totalFavorit = $allRumah->filter(function ($rumah) use ($userId) {
            return $this->isFavorited($rumah, $userId);
        })->count();

        // Lokasi unik
        $lokasiList = Rumah::raw(function ($collection) {
            return $collection->distinct('lokasi');
        });
        $totalLokasi = is_array($lokasiList) ? count($lokasiList) : 0;

        // Rata-rata harga
        $avgHarga = Rumah::avg('harga') ?? 0;

        // Properti terbaru (6 item)
        $latestRumah = Rumah::latest()->take(6)->get();

        // Tandai favorit pada latest
        $latestRumah->transform(function ($item) use ($userId) {
            $item->is_favorit = $this->isFavorited($item, $userId);
            return $item;
        });

        // Favorit user (3 item untuk preview)
        $favoritRumah = $this->getFavoritedRumah($userId, 3);

        return view('pages.dashboard', compact(
            'totalRumah', 'totalFavorit', 'totalLokasi', 'avgHarga',
            'latestRumah', 'favoritRumah'
        ));
    }

    /**
     * Browse all properties with search & filter.
     */
    public function browse(Request $request)
    {
        $user = Auth::user();
        $userId = (string) $user->_id;

        $query = Rumah::query();

        // Search by nama
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }

        // Filter tipe
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter harga
        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', (int) $request->harga_min);
        }
        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', (int) $request->harga_max);
        }

        // Sort
        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'termurah':
                $query->orderBy('harga', 'asc');
                break;
            case 'termahal':
                $query->orderBy('harga', 'desc');
                break;
            default:
                $query->latest();
        }

        $rumahs = $query->paginate(12)->appends($request->query());

        // Tandai favorit
        $rumahs->getCollection()->transform(function ($item) use ($userId) {
            $item->is_favorit = $this->isFavorited($item, $userId);
            return $item;
        });

        // Lokasi list
        $lokasiList = Rumah::raw(function ($collection) {
            return $collection->distinct('lokasi');
        });

        return view('pages.browse', compact('rumahs', 'lokasiList'));
    }

    /**
     * Property detail page.
     */
    public function show($id)
    {
        $rumah = Rumah::findOrFail($id);
        $user = Auth::user();
        $userId = (string) $user->_id;

        $rumah->is_favorit = $this->isFavorited($rumah, $userId);

        // Similar properties (same lokasi or tipe, exclude current)
        $similar = Rumah::where('_id', '!=', $rumah->_id)
            ->where(function ($q) use ($rumah) {
                $q->where('lokasi', $rumah->lokasi)
                  ->orWhere('tipe', $rumah->tipe);
            })
            ->take(3)
            ->get();

        $similar->transform(function ($item) use ($userId) {
            $item->is_favorit = $this->isFavorited($item, $userId);
            return $item;
        });

        return view('pages.detail', compact('rumah', 'similar'));
    }

    /**
     * Toggle favorit (web version).
     */
    public function toggleFavorit($id)
    {
        $rumah = Rumah::findOrFail($id);
        $userId = (string) Auth::user()->_id;
        $currentFavs = $rumah->favorited_user_ids;
        if (!is_array($currentFavs)) $currentFavs = [];

        // Convert all to string for comparison
        $stringFavs = array_map('strval', $currentFavs);

        if (in_array($userId, $stringFavs)) {
            // Remove
            $rumah->favorited_user_ids = array_values(array_filter($stringFavs, fn($f) => $f !== $userId));
            $message = 'Dihapus dari favorit';
        } else {
            // Add
            $stringFavs[] = $userId;
            $rumah->favorited_user_ids = $stringFavs;
            $message = 'Ditambahkan ke favorit ❤️';
        }

        $rumah->save();

        return back()->with('success', $message);
    }

    /**
     * User favorites page.
     */
    public function favorit()
    {
        $userId = (string) Auth::user()->_id;

        // Get all favorited rumah (filtered in PHP because MongoDB array query has type issues)
        $allFavs = $this->getFavoritedRumah($userId);

        // Manual pagination
        $page = request()->get('page', 1);
        $perPage = 12;
        $slice = $allFavs->slice(($page - 1) * $perPage, $perPage)->values();

        $rumahs = new \Illuminate\Pagination\LengthAwarePaginator(
            $slice, $allFavs->count(), $perPage, $page,
            ['path' => request()->url()]
        );

        return view('pages.favorit', compact('rumahs'));
    }

    /**
     * Prediksi Harga page.
     */
    public function prediksi()
    {
        return view('pages.prediksi');
    }

    /**
     * Rekomendasi Personal page (SAW/TOPSIS).
     */
    public function rekomendasi()
    {
        $rumahs = Rumah::all();
        $lokasiList = Rumah::raw(function ($collection) {
            return $collection->distinct('lokasi');
        });

        return view('pages.rekomendasi', compact('rumahs', 'lokasiList'));
    }

    public function wizard()
    {
        $rumahs = Rumah::all();
        $lokasiList = Rumah::raw(function ($collection) {
            return $collection->distinct('lokasi');
        });

        return view('pages.wizard', compact('rumahs', 'lokasiList'));
    }

    /**
     * Bandingkan / Compare properties page.
     */
    public function bandingkan()
    {
        $rumahs = Rumah::all();
        return view('pages.bandingkan', compact('rumahs'));
    }

    /**
     * ML Predict — proxy to Flask ML service (user-accessible).
     */
    public function mlPredict(Request $request)
    {
        $validated = $request->validate([
            'luas_tanah'    => 'required|numeric|min:1',
            'luas_bangunan' => 'required|numeric|min:1',
            'kamar_tidur'   => 'required|integer|min:1',
            'kamar_mandi'   => 'required|integer|min:1',
            'lokasi'        => 'required|string',
        ]);

        try {
            $response = Http::timeout(10)->post('http://127.0.0.1:5000/predict', $validated);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'ML Service tidak tersedia',
            ], 503);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak dapat terhubung ke ML Service (Flask port 5000)',
            ], 503);
        }
    }

    /**
     * ML Recommend — TOPSIS via Flask ML service (user-accessible).
     */
    public function mlRecommend(Request $request)
    {
        try {
            // Ambil semua data rumah dari database
            $allRumah = Rumah::all()->map(function ($r) {
                return [
                    '_id'            => (string) $r->_id,
                    'nama'           => $r->nama,
                    'lokasi'         => $r->lokasi,
                    'harga'          => $r->harga ?? 0,
                    'luas_tanah'     => $r->luas_tanah ?? 0,
                    'luas_bangunan'  => $r->luas_bangunan ?? 0,
                    'kamar_tidur'    => $r->kamar_tidur ?? 0,
                    'kamar_mandi'    => $r->kamar_mandi ?? 0,
                    'tipe'           => $r->tipe ?? '',
                    'foto'           => $r->foto ?? null,
                ];
            })->toArray();

            $payload = [
                'properties'    => array_values($allRumah),
                'weights'       => $request->input('weights', []),
                'budget_max'    => $request->input('budget_max', 0),
                'lokasi_filter' => $request->input('lokasi_filter', ''),
            ];

            $response = Http::timeout(15)->post('http://127.0.0.1:5000/recommend', $payload);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'ML Service tidak tersedia',
            ], 503);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak dapat terhubung ke ML Service: ' . $e->getMessage(),
            ], 503);
        }
    }
}
