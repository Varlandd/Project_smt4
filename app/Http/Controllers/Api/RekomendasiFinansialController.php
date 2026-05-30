<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Rumah;

class RekomendasiFinansialController extends Controller
{
    public function hitung(Request $request)
    {
        $validated = $request->validate([
            'pendapatan'     => 'required|numeric|min:0',
            'pengeluaran'    => 'required|numeric|min:0',
            'kamar_tidur'    => 'required|integer|min:1',
            'kamar_mandi'    => 'required|integer|min:1',
            'luas_tanah'     => 'required|numeric|min:1',
            'luas_bangunan'  => 'required|numeric|min:1',
            'kota'           => 'required|string',
            'posisi_kota'    => 'required|string',
        ]);

        $pendapatan = (float) $validated['pendapatan'];
        $pengeluaran = (float) $validated['pengeluaran'];
        $pendapatanBersih = $pendapatan - $pengeluaran;

        if ($pendapatanBersih <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Pendapatan bersih harus lebih dari 0.',
            ], 422);
        }

        // Rumus: Budget = 3 × (Pendapatan Bersih × 12)
        $budget = 3 * ($pendapatanBersih * 12);

        // ── Kirim ke ML Service untuk mendapatkan cluster ──
        $clusterId = null;
        $kategori = null;
        $mlStatus = 'offline';

        try {
            $mlPayload = [
                'harga'          => (int) $budget,
                'luas_tanah'     => (float) $validated['luas_tanah'],
                'luas_bangunan'  => (float) $validated['luas_bangunan'],
                'kamar_tidur'    => (int) $validated['kamar_tidur'],
                'kamar_mandi'    => (int) $validated['kamar_mandi'],
                'kota'           => $validated['kota'],
                'posisi_kota'    => $validated['posisi_kota'],
            ];

            $mlResponse = Http::timeout(10)->post('http://127.0.0.1:5000/predict', $mlPayload);

            if ($mlResponse->successful()) {
                $mlResult = $mlResponse->json();
                $clusterId = $mlResult['predicted_cluster'] ?? null;
                $kategori = $mlResult['kategori'] ?? null;
                $mlStatus = 'online';
            }
        } catch (\Exception $e) {
            $mlStatus = 'offline';
        }

        // ── Cari rumah sesuai budget + kriteria ──
        $reqKota = $validated['kota'];
        $isFallback = false;

        if ($clusterId !== null) {
            // Prioritas 1: cluster + kota + budget
            $rumahs = Rumah::where('cluster_harga', (int) $clusterId)
                           ->where('kota', $reqKota)
                           ->where('harga', '<=', (int) $budget)
                           ->take(18)
                           ->get()
                           ->sortBy(function ($r) use ($budget) {
                               return abs(($r->harga ?? 0) - $budget);
                           })->take(12)->values();

            // Fallback 2: cluster + budget (semua kota)
            if ($rumahs->count() === 0) {
                $isFallback = true;
                $rumahs = Rumah::where('cluster_harga', (int) $clusterId)
                               ->where('harga', '<=', (int) $budget)
                               ->take(18)
                               ->get()
                               ->sortBy(function ($r) use ($budget) {
                                   return abs(($r->harga ?? 0) - $budget);
                               })->take(12)->values();
            }

            // Fallback 3: budget only
            if ($rumahs->count() === 0) {
                $rumahs = Rumah::where('harga', '<=', (int) $budget)
                               ->orderBy('harga', 'desc')
                               ->take(12)
                               ->get();
            }
        } else {
            // ML offline: fallback ke query budget + kota
            $rumahs = Rumah::where('harga', '<=', (int) $budget)
                           ->where('kota', $reqKota)
                           ->orderBy('harga', 'desc')
                           ->take(12)
                           ->get();

            // Fallback: budget saja
            if ($rumahs->count() === 0) {
                $isFallback = true;
                $rumahs = Rumah::where('harga', '<=', (int) $budget)
                               ->orderBy('harga', 'desc')
                               ->take(12)
                               ->get();
            }
        }

        // Return JSON data (bukan HTML, karena ini API untuk mobile)
        return response()->json([
            'success'           => true,
            'pendapatan_bersih' => $pendapatanBersih,
            'budget'            => $budget,
            'total_rumah'       => $rumahs->count(),
            'predicted_cluster' => $clusterId,
            'kategori'          => $kategori,
            'ml_status'         => $mlStatus,
            'is_fallback'       => $isFallback,
            'req_kota'          => $reqKota,
            'data'              => $rumahs->values()->toArray(),
        ]);
    }
}
