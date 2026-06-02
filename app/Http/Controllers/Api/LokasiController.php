<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rumah;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Rumah::raw(function ($collection) {
            return collect($collection->distinct('kota'))->sort()->values();
        });

        return response()->json([
            'success' => true,
            'data' => $lokasi,
        ]);
    }
}
