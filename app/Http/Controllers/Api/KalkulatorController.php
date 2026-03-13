<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KalkulatorController extends Controller
{
    /**
     * Hitung budget KPR.
     * Aturan: max cicilan = 30% dari penghasilan - cicilan lain
     */
    public function hitung(Request $request)
    {
        $validated = $request->validate([
            'penghasilan' => 'required|integer|min:0',
            'uang_muka' => 'required|integer|min:0',
            'cicilan_lain' => 'nullable|integer|min:0',
            'tenor' => 'required|integer|min:1|max:30',
        ]);

        $penghasilan = $validated['penghasilan'];
        $uangMuka = $validated['uang_muka'];
        $cicilanLain = $validated['cicilan_lain'] ?? 0;
        $tenorTahun = $validated['tenor'];

        // Max cicilan = 30% penghasilan - cicilan lain
        $maxCicilan = (int)($penghasilan * 0.3) - $cicilanLain;
        if ($maxCicilan < 0)
            $maxCicilan = 0;

        // Pokok pinjaman = max cicilan * jumlah bulan
        $jumlahBulan = $tenorTahun * 12;
        $pokokPinjaman = $maxCicilan * $jumlahBulan;

        // Budget rumah = pokok pinjaman + uang muka
        $budgetRumah = $pokokPinjaman + $uangMuka;

        // Cicilan per bulan (simplified, tanpa bunga untuk saat ini)
        $cicilanPerBulan = $jumlahBulan > 0 ? (int)($pokokPinjaman / $jumlahBulan) : 0;

        // Sisa pendapatan
        $sisaPendapatan = $penghasilan - $cicilanPerBulan - $cicilanLain;

        return response()->json([
            'success' => true,
            'data' => [
                'budget_rumah' => $budgetRumah,
                'cicilan_per_bulan' => $cicilanPerBulan,
                'sisa_pendapatan' => $sisaPendapatan,
                'max_cicilan' => $maxCicilan,
                'pokok_pinjaman' => $pokokPinjaman,
                'uang_muka' => $uangMuka,
                'tenor_tahun' => $tenorTahun,
            ],
        ]);
    }
}
