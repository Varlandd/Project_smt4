<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rumah;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index()
    {
        // ── Summary Cards ──
        $totalRumah   = Rumah::count();
        $totalUser    = User::where('role', 'user')->count();
        $totalAdmin   = User::where('role', 'admin')->count();
        $totalFavorit = DB::table('favorits')->count();

        // ── Distribusi Rumah per Lokasi (Bar Chart) ──
        $perLokasi = Rumah::selectRaw('lokasi, COUNT(*) as jumlah')
            ->groupBy('lokasi')
            ->orderByDesc('jumlah')
            ->get();

        // ── Rata-rata Harga per Tipe (Bar Chart) ──
        $perTipe = Rumah::selectRaw('tipe, AVG(harga) as avg_harga, COUNT(*) as jumlah')
            ->groupBy('tipe')
            ->orderByDesc('avg_harga')
            ->get();

        // ── Distribusi Segmen Harga (Doughnut) ──
        $segmenHarga = [
            'Murah (< 500Jt)'       => Rumah::where('harga', '<', 500000000)->count(),
            'Menengah (500Jt - 1M)'  => Rumah::whereBetween('harga', [500000000, 1000000000])->count(),
            'Premium (1M - 2M)'     => Rumah::whereBetween('harga', [1000000001, 2000000000])->count(),
            'Mewah (> 2M)'          => Rumah::where('harga', '>', 2000000000)->count(),
        ];

        // ── Top 5 Rumah Paling Difavoritkan ──
        $topFavorit = Rumah::withCount('favoritedBy')
            ->orderByDesc('favorited_by_count')
            ->limit(5)
            ->get();

        // ── Aktivitas Pendaftaran User per Bulan (Line Chart) ──
        $userPerBulan = User::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as jumlah")
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('bulan')
            ->limit(12)
            ->get();

        // ── 5 Properti Terbaru ──
        $rumahTerbaru = Rumah::latest()->limit(5)->get();

        // ── Statistik Tambahan ──
        $hargaTertinggi  = Rumah::max('harga');
        $hargaTerendah   = Rumah::min('harga');
        $avgHarga        = Rumah::avg('harga');
        $avgLuasTanah    = Rumah::avg('luas_tanah');
        $avgLuasBangunan = Rumah::avg('luas_bangunan');

        return view('admin.pages.statistik', compact(
            'totalRumah', 'totalUser', 'totalAdmin', 'totalFavorit',
            'perLokasi', 'perTipe', 'segmenHarga',
            'topFavorit', 'userPerBulan', 'rumahTerbaru',
            'hargaTertinggi', 'hargaTerendah', 'avgHarga',
            'avgLuasTanah', 'avgLuasBangunan'
        ));
    }
}
