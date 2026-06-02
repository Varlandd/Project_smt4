<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rumah;
use App\Models\User;

class StatistikController extends Controller
{
    public function index()
    {
        // ── Summary Cards ──
        $totalRumah   = Rumah::count();
        $totalUser    = User::where('role', 'user')->count();
        $totalAdmin   = User::where('role', 'admin')->count();

        // Hitung total favorit dari embedded array
        $totalFavorit = 0;
        $allRumah = Rumah::whereNotNull('favorited_user_ids')->get();
        foreach ($allRumah as $r) {
            $favs = $r->favorited_user_ids;
            $totalFavorit += is_array($favs) ? count($favs) : 0;
        }

        // ── Distribusi Rumah per Kota (Bar Chart) ──
        $perLokasiRaw = Rumah::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => ['_id' => '$kota', 'jumlah' => ['$sum' => 1]]],
                ['$sort'  => ['jumlah' => -1]],
            ]);
        });
        $perLokasi = $perLokasiRaw->map(fn($d) => (object)['lokasi' => $d['_id'] ?? 'Lainnya', 'jumlah' => $d['jumlah']]);

        // ── Rata-rata Harga per Kota (Bar Chart) ──
        $perKotaHargaRaw = Rumah::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => [
                    '_id'       => '$kota',
                    'avg_harga' => ['$avg' => '$harga'],
                    'jumlah'    => ['$sum' => 1],
                ]],
                ['$sort' => ['avg_harga' => -1]],
            ]);
        });
        $perKotaHarga = $perKotaHargaRaw->map(fn($d) => (object)[
            'kota'      => $d['_id'] ?? 'Lainnya',
            'avg_harga' => $d['avg_harga'],
            'jumlah'    => $d['jumlah'],
        ]);

        // ── Rata-rata Harga per Tipe (Bar Chart) ──
        $perTipeRaw = Rumah::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => [
                    '_id'       => '$tipe',
                    'avg_harga' => ['$avg' => '$harga'],
                    'jumlah'    => ['$sum' => 1],
                ]],
                ['$sort' => ['avg_harga' => -1]],
            ]);
        });
        $perTipe = $perTipeRaw->map(fn($d) => (object)[
            'tipe'      => $d['_id'],
            'avg_harga' => $d['avg_harga'],
            'jumlah'    => $d['jumlah'],
        ]);

        // ── Distribusi Segmen Harga (Doughnut) ──
        $segmenHarga = [
            'Murah (< 500Jt)'       => Rumah::where('harga', '<', 500000000)->count(),
            'Menengah (500Jt - 1M)'  => Rumah::whereBetween('harga', [500000000, 1000000000])->count(),
            'Premium (1M - 2M)'     => Rumah::whereBetween('harga', [1000000001, 2000000000])->count(),
            'Mewah (> 2M)'          => Rumah::where('harga', '>', 2000000000)->count(),
        ];

        // ── Top 10 Rumah Paling Difavoritkan ──
        $allRumahForFav = Rumah::all()->map(function ($r) {
            $favs = $r->favorited_user_ids;
            $r->favorited_by_count = is_array($favs) ? count($favs) : 0;
            return $r;
        })->sortByDesc('favorited_by_count')->take(10)->values();
        $topFavorit = $allRumahForFav;

        // ── Aktivitas Pendaftaran User per Bulan (Line Chart) ──
        $userPerBulanRaw = User::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => [
                    '_id'    => ['$dateToString' => ['format' => '%Y-%m', 'date' => '$created_at']],
                    'jumlah' => ['$sum' => 1],
                ]],
                ['$sort'  => ['_id' => 1]],
                ['$limit' => 12],
            ]);
        });
        $userPerBulan = $userPerBulanRaw->map(fn($d) => (object)['bulan' => $d['_id'], 'jumlah' => $d['jumlah']]);

        // ── Distribusi Kamar Tidur ──
        $kamarTidurRaw = Rumah::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => ['_id' => '$kamar_tidur', 'jumlah' => ['$sum' => 1]]],
                ['$sort'  => ['_id' => 1]],
            ]);
        });
        $kamarTidur = $kamarTidurRaw->map(fn($d) => (object)['kamar' => $d['_id'] ?? 0, 'jumlah' => $d['jumlah']]);

        // ── 5 Properti Terbaru ──
        $rumahTerbaru = Rumah::latest()->limit(5)->get();

        // ── 5 Properti Termahal ──
        $rumahTermahal = Rumah::orderBy('harga', 'desc')->limit(5)->get();

        // ── Statistik Tambahan ──
        $hargaTertinggi  = Rumah::max('harga');
        $hargaTerendah   = Rumah::min('harga');
        $avgHarga        = Rumah::avg('harga');
        $avgLuasTanah    = Rumah::avg('luas_tanah');
        $avgLuasBangunan = Rumah::avg('luas_bangunan');
        $avgKamarTidur   = Rumah::avg('kamar_tidur');
        $avgKamarMandi   = Rumah::avg('kamar_mandi');
        $totalKota       = Rumah::raw(function ($collection) {
            return $collection->distinct('kota');
        });
        $totalKota = is_array($totalKota) ? count($totalKota) : 0;

        return view('admin.pages.statistik', compact(
            'totalRumah', 'totalUser', 'totalAdmin', 'totalFavorit', 'totalKota',
            'perLokasi', 'perKotaHarga', 'perTipe', 'segmenHarga', 'kamarTidur',
            'topFavorit', 'userPerBulan', 'rumahTerbaru', 'rumahTermahal',
            'hargaTertinggi', 'hargaTerendah', 'avgHarga',
            'avgLuasTanah', 'avgLuasBangunan', 'avgKamarTidur', 'avgKamarMandi'
        ));
    }
}
