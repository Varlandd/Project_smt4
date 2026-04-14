<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Rumah;

class AnalitikController extends Controller
{
    private $mlApiUrl = 'http://127.0.0.1:5000';

    public function index()
    {
        $totalRumah = Rumah::count();
        $avgHarga   = Rumah::avg('harga');

        // Hitung distribusi harga per lokasi dari database (MongoDB aggregation)
        $hargaPerLokasiRaw = Rumah::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => [
                    '_id'       => '$lokasi',
                    'rata_rata' => ['$avg' => '$harga'],
                    'jumlah'    => ['$sum' => 1],
                    'min_harga' => ['$min' => '$harga'],
                    'max_harga' => ['$max' => '$harga'],
                ]],
                ['$sort' => ['rata_rata' => -1]],
                ['$limit' => 10],
            ]);
        });

        $hargaPerLokasi = $hargaPerLokasiRaw->map(fn($d) => (object)[
            'lokasi'    => $d['_id'],
            'rata_rata' => $d['rata_rata'],
            'jumlah'    => $d['jumlah'],
            'min_harga' => $d['min_harga'],
            'max_harga' => $d['max_harga'],
        ]);

        $prediksiWilayah = [
            'labels' => $hargaPerLokasi->pluck('lokasi')->toArray(),
            'data'   => $hargaPerLokasi->pluck('rata_rata')->map(fn($v) => (int) $v)->toArray(),
        ];

        // Data kenaikan harga per wilayah (simulasi berdasarkan data)
        $kenaikanPerWilayah = [];
        $lokasi_list = ['Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Utara', 'Jakarta Pusat',
                        'Depok', 'Tangerang', 'Tangerang Selatan', 'Bekasi', 'Bogor'];

        foreach ($lokasi_list as $lok) {
            $dataLokasi = $hargaPerLokasi->firstWhere('lokasi', $lok);
            $avgHargaLok = $dataLokasi ? (int)$dataLokasi->rata_rata : 0;
            $jumlahLok   = $dataLokasi ? $dataLokasi->jumlah : 0;
            $minHarga    = $dataLokasi ? (int)$dataLokasi->min_harga : 0;
            $maxHarga    = $dataLokasi ? (int)$dataLokasi->max_harga : 0;

            // Simulasi kenaikan tahunan berdasarkan lokasi
            $kenaikan = match(true) {
                str_contains($lok, 'Jakarta Selatan') => 15.2,
                str_contains($lok, 'Jakarta Pusat')   => 14.8,
                str_contains($lok, 'Tangerang Selatan') => 13.5,
                str_contains($lok, 'Jakarta Barat')   => 12.1,
                str_contains($lok, 'Jakarta Timur')   => 11.8,
                str_contains($lok, 'Jakarta Utara')   => 10.5,
                str_contains($lok, 'Tangerang')       => 11.2,
                str_contains($lok, 'Bekasi')          => 9.8,
                str_contains($lok, 'Depok')           => 10.3,
                str_contains($lok, 'Bogor')           => 8.7,
                default                               => 10.0,
            };

            $kenaikanPerWilayah[$lok] = [
                'kenaikan'   => $kenaikan,
                'avg_harga'  => $avgHargaLok,
                'jumlah'     => $jumlahLok,
                'min_harga'  => $minHarga,
                'max_harga'  => $maxHarga,
            ];
        }

        // Tren harga berdasarkan waktu (MongoDB aggregation)
        $trendBulananRaw = Rumah::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => [
                    '_id'       => ['$dateToString' => ['format' => '%Y-%m', 'date' => '$created_at']],
                    'rata_rata' => ['$avg' => '$harga'],
                ]],
                ['$sort'  => ['_id' => 1]],
                ['$limit' => 9],
            ]);
        });

        if ($trendBulananRaw->count() < 2) {
            $trendHarga = [
                'labels'  => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul (Prediksi)', 'Agt (Prediksi)', 'Sep (Prediksi)'],
                'actual'  => [300000000, 305000000, 312000000, 310000000, 320000000, 325000000, null, null, null],
                'predict' => [null, null, null, null, null, 325000000, 335000000, 342000000, 350000000],
            ];
        } else {
            $labels  = $trendBulananRaw->pluck('_id')->toArray();
            $actuals = $trendBulananRaw->pluck('rata_rata')->map(fn($v) => (int) $v)->toArray();
            $lastPrice = end($actuals);
            $predictPrices = array_fill(0, count($actuals) - 1, null);
            $predictPrices[] = $lastPrice;
            for ($i = 1; $i <= 3; $i++) {
                $predictPrices[] = (int)($lastPrice * pow(1.03, $i));
                $labels[] = 'Prediksi +' . $i . ' Bulan';
                $actuals[] = null;
            }
            $trendHarga = [
                'labels'  => $labels,
                'actual'  => $actuals,
                'predict' => $predictPrices,
            ];
        }

        // Distribusi tipe rumah (MongoDB aggregation)
        $distribusiTipeRaw = Rumah::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => ['_id' => '$tipe', 'jumlah' => ['$sum' => 1]]],
            ]);
        });
        $distribusiTipe = $distribusiTipeRaw->map(fn($d) => (object)['tipe' => $d['_id'], 'jumlah' => $d['jumlah']]);

        // Cek status ML API
        $mlStatus = 'offline';
        try {
            $response = Http::timeout(3)->get($this->mlApiUrl);
            if ($response->successful()) {
                $mlStatus = 'online';
            }
        } catch (\Exception $e) {
            $mlStatus = 'offline';
        }

        return view('admin.pages.analitik', compact(
            'prediksiWilayah',
            'trendHarga',
            'distribusiTipe',
            'mlStatus',
            'kenaikanPerWilayah',
            'totalRumah',
            'avgHarga'
        ));
    }

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
                return response()->json($response->json());
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'ML Service unavailable',
            ], 503);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak dapat terhubung ke ML Service. Pastikan Flask server berjalan di port 5000.',
            ], 503);
        }
    }
}
