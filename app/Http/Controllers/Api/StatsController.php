<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rumah;

class StatsController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_rumah' => Rumah::count(),
                'rata_rata_harga' => (int)Rumah::avg('harga'),
                'harga_tertinggi' => (int)Rumah::max('harga'),
                'harga_terendah' => (int)Rumah::min('harga'),
                'total_lokasi' => Rumah::distinct('lokasi')->count('lokasi'),
                'tipe_tersedia' => Rumah::distinct('tipe')->pluck('tipe'),
            ],
        ]);
    }
}
