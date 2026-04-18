<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rumah;
use App\Models\User;
use App\Models\Pesan;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Summary Stats ──
        $totalRumah   = Rumah::count();
        $totalUser    = User::count();
        $totalAdmin   = User::where('role', 'admin')->count();
        $totalPesan   = Pesan::count();
        $unreadPesan  = Pesan::where('status', 'unread')->count();

        // Hitung total favorit dari field favorited_user_ids
        $totalFavorit = Rumah::raw(function ($collection) {
            return $collection->aggregate([
                ['$addFields' => ['fav_arr' => ['$cond' => [
                    ['$isArray' => '$favorited_user_ids'],
                    '$favorited_user_ids',
                    []
                ]]]],
                ['$project' => ['count' => ['$size' => '$fav_arr']]],
                ['$group'   => ['_id' => null, 'total' => ['$sum' => '$count']]],
            ]);
        })->first()['total'] ?? 0;

        // ── Quick Price Stats ──
        $hargaTertinggi = Rumah::max('harga');
        $hargaTerendah  = Rumah::min('harga');
        $avgHarga       = Rumah::avg('harga');

        // ── Top 5 Rumah Terfavorit ──
        $topFavorit = Rumah::raw(function ($collection) {
            return $collection->aggregate([
                ['$addFields' => ['fav_count' => ['$cond' => [
                    ['$isArray' => '$favorited_user_ids'],
                    ['$size' => '$favorited_user_ids'],
                    0
                ]]]],
                ['$sort'      => ['fav_count' => -1]],
                ['$limit'     => 5],
            ]);
        })->map(fn($d) => new Rumah((array) $d));

        // ── 5 Properti Terbaru ──
        $rumahTerbaru = Rumah::latest()->limit(5)->get();

        // ── 5 Pengguna Terbaru ──
        $userTerbaru = User::latest()->limit(5)->get();

        // ── Mini Trend Data (sparkline) – group by year-month ──
        $trendRaw = Rumah::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => [
                    '_id'      => ['$dateToString' => ['format' => '%Y-%m', 'date' => '$created_at']],
                    'rata_rata' => ['$avg' => '$harga'],
                    'jumlah'    => ['$sum' => 1],
                ]],
                ['$sort'  => ['_id' => 1]],
                ['$limit' => 6],
            ]);
        });

        if ($trendRaw->count() < 2) {
            $trendData = [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                'data'   => [300, 305, 312, 310, 320, 325],
            ];
        } else {
            $trendData = [
                'labels' => $trendRaw->pluck('_id')->toArray(),
                'data'   => $trendRaw->map(fn($v) => round($v['rata_rata'] / 1_000_000))->toArray(),
            ];
        }

        // ── Distribusi per Lokasi ──
        $perLokasiRaw = Rumah::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => ['_id' => '$lokasi', 'jumlah' => ['$sum' => 1]]],
                ['$sort'  => ['jumlah' => -1]],
                ['$limit' => 5],
            ]);
        });

        $perLokasi = $perLokasiRaw->map(fn($d) => (object)['lokasi' => $d['_id'], 'jumlah' => $d['jumlah']]);

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
            'trendData', 'perLokasi', 'mlStatus',
            'totalPesan', 'unreadPesan'
        ));
    }
}
