<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rumah;

class RumahController extends Controller
{
    /**
     * Tampilkan landing page dengan data dinamis.
     */
    public function index()
    {
        // Total data properti
        $totalRumah = Rumah::count();
        
        // Ambil salah satu rumah terfavorit untuk ditampilkan di hero section
        // (Berdasarkan jumlah array favorited_user_ids terbanyak)
        $featuredRumah = Rumah::raw(function ($collection) {
            return $collection->aggregate([
                ['$addFields' => ['fav_count' => ['$cond' => [
                    ['$isArray' => '$favorited_user_ids'],
                    ['$size' => '$favorited_user_ids'],
                    0
                ]]]],
                ['$sort'      => ['fav_count' => -1]],
                ['$limit'     => 1],
            ]);
        })->map(fn($d) => new Rumah((array) $d))->first();

        // Jika tidak ada Featured, ambil rumah terbaru saja
        if (!$featuredRumah) {
            $featuredRumah = Rumah::latest()->first();
        }

        // Ambil 6 properti terbaru untuk showcase
        $latestRumah = Rumah::latest()->limit(6)->get();

        // Hitung jumlah lokasi unik
        $totalLokasi = Rumah::distinct('lokasi')->count();

        return view('pages.landing', compact('totalRumah', 'featuredRumah', 'latestRumah', 'totalLokasi'));
    }

    
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

    /**
     * Handle public contact form submission.
     */
    public function contact(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subjek' => 'required|string|max:150',
            'pesan' => 'required|string',
        ]);

        \App\Models\Pesan::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'subjek' => $request->subjek,
            'pesan' => $request->pesan,
            'status' => 'unread',
        ]);

        return redirect()->back()->with('success', 'Pesan Anda telah terkirim! Admin akan segera meninjau.');
    }
}
