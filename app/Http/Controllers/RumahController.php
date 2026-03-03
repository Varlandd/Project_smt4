<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RumahController extends Controller
{
    /**
     * Handle pencarian rumah dari form landing page.
     * Akan diintegrasikan dengan model ML untuk prediksi dan ranking.
     */
    public function search(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|max:100',
            'phone'       => 'nullable|string|max:20',
            'lokasi'      => 'required|string',
            'budget_min'  => 'nullable|numeric',
            'budget_max'  => 'nullable|numeric',
            'tipe'        => 'nullable|string',
            'fasilitas'   => 'nullable|array',
        ]);

        // TODO: Integrasikan dengan sistem rekomendasi
        // 1. Panggil model Multiple Linear Regression untuk prediksi harga
        // 2. Gunakan SAW/TOPSIS untuk ranking rumah
        // 3. Filter berdasarkan kriteria user
        
        // Contoh response (sementara redirect dengan pesan)
        return redirect()->route('home')
            ->with('success', "Terima kasih {$validated['nama']}! Kami sedang memproses pencarian rumah terbaik untuk Anda. Hasil akan dikirim ke email {$validated['email']}.")
            ->withFragment('contact');
    }
}
