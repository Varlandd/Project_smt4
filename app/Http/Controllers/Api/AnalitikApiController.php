<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Rumah;

class AnalitikApiController extends Controller
{
    private $mlApiUrl = 'http://127.0.0.1:5000';

    /**
     * Prediksi harga menggunakan ML Service.
     */
    public function predict(Request $request)
    {
        $validated = $request->validate([
            'luas_tanah'    => 'required|numeric|min:1',
            'luas_bangunan' => 'required|numeric|min:1',
            'kamar_tidur'   => 'required|integer|min:1',
            'kamar_mandi'   => 'required|integer|min:1',
            'lokasi'        => 'required|string',
        ]);

        try {
            $response = Http::timeout(10)->post($this->mlApiUrl . '/predict', $validated);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data'    => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'ML Service unavailable',
            ], 503);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat terhubung ke ML Service.',
            ], 503);
        }
    }

    /**
     * Rekomendasi rumah menggunakan metode SAW.
     */
    public function recommend(Request $request)
    {
        $validated = $request->validate([
            'lokasi'      => 'nullable|string',
            'budget_max'  => 'nullable|numeric',
            'w_harga'     => 'nullable|numeric|min:1|max:5',
            'w_tanah'     => 'nullable|numeric|min:1|max:5',
            'w_bangunan'  => 'nullable|numeric|min:1|max:5',
            'w_kamar'     => 'nullable|numeric|min:1|max:5',
        ]);

        $query = Rumah::query();

        if (!empty($validated['lokasi'])) {
            $query->where('lokasi', $validated['lokasi']);
        }

        if (!empty($validated['budget_max'])) {
            $query->where('harga', '<=', $validated['budget_max']);
        }

        $rumahs = $query->get();

        if ($rumahs->isEmpty()) {
            return response()->json(['success' => true, 'data' => []]);
        }

        // Bobot (Default 3)
        $wHarga    = $validated['w_harga'] ?? 3;
        $wTanah    = $validated['w_tanah'] ?? 3;
        $wBangunan = $validated['w_bangunan'] ?? 3;
        $wKamar    = $validated['w_kamar'] ?? 3;
        $totalW    = $wHarga + $wTanah + $wBangunan + $wKamar;

        // Cari Min/Max untuk normalisasi
        $minHarga    = $rumahs->min('harga') ?: 1;
        $maxTanah    = $rumahs->max('luas_tanah') ?: 1;
        $maxBangunan = $rumahs->max('luas_bangunan') ?: 1;
        $maxKamar    = $rumahs->max('kamar_tidur') ?: 1;

        $ranked = $rumahs->map(function ($r) use ($minHarga, $maxTanah, $maxBangunan, $maxKamar, $wHarga, $wTanah, $wBangunan, $wKamar, $totalW) {
            // Normalisasi
            $nHarga    = $minHarga / ($r->harga ?: 1); // Cost
            $nTanah    = ($r->luas_tanah ?: 0) / $maxTanah; // Benefit
            $nBangunan = ($r->luas_bangunan ?: 0) / $maxBangunan; // Benefit
            $nKamar    = ($r->kamar_tidur ?: 0) / $maxKamar; // Benefit

            $r->score = (($wHarga / $totalW) * $nHarga) +
                        (($wTanah / $totalW) * $nTanah) +
                        (($wBangunan / $totalW) * $nBangunan) +
                        (($wKamar / $totalW) * $nKamar);
            
            return $r;
        });

        $sorted = $ranked->sortByDesc('score')->values();

        return response()->json([
            'success' => true,
            'data'    => $sorted
        ]);
    }
}
