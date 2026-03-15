<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rumah;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Summary Stats ──
        $totalRumah   = Rumah::count();
        $totalUser    = User::count();
        $totalFavorit = DB::table('favorits')->count();
        $totalAdmin   = User::where('role', 'admin')->count();

        // ── Quick Price Stats ──
        $hargaTertinggi = Rumah::max('harga');
        $hargaTerendah  = Rumah::min('harga');
        $avgHarga       = Rumah::avg('harga');

        // ── Top 5 Rumah Terfavorit ──
        $topFavorit = Rumah::withCount('favoritedBy')
            ->orderByDesc('favorited_by_count')
            ->limit(5)
            ->get();

        // ── 5 Properti Terbaru ──
        $rumahTerbaru = Rumah::latest()->limit(5)->get();

        // ── 5 Pengguna Terbaru ──
        $userTerbaru = User::latest()->limit(5)->get();

        // ── Mini Trend Data (untuk sparkline chart) ──
        $trendBulanan = Rumah::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, AVG(harga) as rata_rata, COUNT(*) as jumlah")
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('bulan')
            ->limit(6)
            ->get();

        // fallback mock data jika belum cukup
        if ($trendBulanan->count() < 2) {
            $trendData = [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                'data'   => [300, 305, 312, 310, 320, 325],
            ];
        } else {
            $trendData = [
                'labels' => $trendBulanan->pluck('bulan')->toArray(),
                'data'   => $trendBulanan->pluck('rata_rata')->map(fn($v) => round($v / 1000000))->toArray(),
            ];
        }

        // ── Distribusi per Lokasi (untuk pie chart kecil) ──
        $perLokasi = Rumah::selectRaw('lokasi, COUNT(*) as jumlah')
            ->groupBy('lokasi')
            ->orderByDesc('jumlah')
            ->limit(5)
            ->get();

        // ── ML Status ──
        $mlStatus = 'offline';
        try {
            $res = Http::timeout(2)->get('http://127.0.0.1:5000');
            if ($res->successful()) $mlStatus = 'online';
        } catch (\Exception $e) {}

        return view('admin.pages.dashboard', compact(
            'totalRumah', 'totalUser', 'totalFavorit', 'totalAdmin',
            'hargaTertinggi', 'hargaTerendah', 'avgHarga',
            'topFavorit', 'rumahTerbaru', 'userTerbaru',
            'trendData', 'perLokasi', 'mlStatus'
        ));
    }
}
