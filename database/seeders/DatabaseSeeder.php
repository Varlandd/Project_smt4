<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use App\Models\Rumah;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── User Demo ──
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@rumahku.com',
            'password' => 'password',
            'phone' => '081234567890',
        ]);

        // ── Fasilitas ──
        $fasilitasNames = [
            'Kolam Renang', 'Taman', 'Garasi', 'Keamanan 24 Jam',
            'Masjid Terdekat', 'Playground', 'Gym', 'Rooftop',
        ];

        $fasilitasList = [];
        foreach ($fasilitasNames as $nama) {
            $fasilitasList[] = Fasilitas::create(['nama' => $nama]);
        }

        // ── Rumah ──
        $rumahData = [
            [
                'nama' => 'Rumah Asri Citra Indah',
                'lokasi' => 'Bandung',
                'harga' => 850000000,
                'luas_tanah' => 120,
                'luas_bangunan' => 90,
                'kamar_tidur' => 3,
                'kamar_mandi' => 2,
                'tipe' => 'Rumah',
                'deskripsi' => 'Rumah asri dengan taman luas di kawasan Citra Indah Bandung. Dekat dengan pusat perbelanjaan dan sekolah.',
                'fasilitas' => [0, 1, 2, 3], // Kolam, Taman, Garasi, Keamanan
            ],
            [
                'nama' => 'Apartemen Skyline Tower',
                'lokasi' => 'Jakarta Selatan',
                'harga' => 1200000000,
                'luas_tanah' => 0,
                'luas_bangunan' => 65,
                'kamar_tidur' => 2,
                'kamar_mandi' => 1,
                'tipe' => 'Apartemen',
                'deskripsi' => 'Apartemen modern dengan pemandangan kota Jakarta. Fully furnished dan akses mudah ke MRT.',
                'fasilitas' => [0, 3, 6, 7], // Kolam, Keamanan, Gym, Rooftop
            ],
            [
                'nama' => 'Cluster Harmoni Residence',
                'lokasi' => 'Depok',
                'harga' => 650000000,
                'luas_tanah' => 80,
                'luas_bangunan' => 60,
                'kamar_tidur' => 2,
                'kamar_mandi' => 1,
                'tipe' => 'Rumah',
                'deskripsi' => 'Hunian nyaman di cluster eksklusif Depok. Akses dekat stasiun KRL dan toll.',
                'fasilitas' => [1, 2, 3, 5], // Taman, Garasi, Keamanan, Playground
            ],
            [
                'nama' => 'Ruko Golden Boulevard',
                'lokasi' => 'Tangerang',
                'harga' => 2100000000,
                'luas_tanah' => 75,
                'luas_bangunan' => 200,
                'kamar_tidur' => 0,
                'kamar_mandi' => 2,
                'tipe' => 'Ruko',
                'deskripsi' => 'Ruko strategis di kawasan bisnis BSD City. 3 lantai, cocok untuk usaha atau kantor.',
                'fasilitas' => [2, 3], // Garasi, Keamanan
            ],
            [
                'nama' => 'Villa Puncak Indah',
                'lokasi' => 'Bogor',
                'harga' => 1500000000,
                'luas_tanah' => 300,
                'luas_bangunan' => 150,
                'kamar_tidur' => 4,
                'kamar_mandi' => 3,
                'tipe' => 'Villa',
                'deskripsi' => 'Villa eksklusif di kawasan Puncak dengan pemandangan pegunungan. Udara sejuk dan suasana asri.',
                'fasilitas' => [0, 1, 2, 5], // Kolam, Taman, Garasi, Playground
            ],
            [
                'nama' => 'Griya Mentari Permai',
                'lokasi' => 'Bekasi',
                'harga' => 450000000,
                'luas_tanah' => 60,
                'luas_bangunan' => 45,
                'kamar_tidur' => 2,
                'kamar_mandi' => 1,
                'tipe' => 'Rumah',
                'deskripsi' => 'Rumah subsidi berkualitas di kawasan Bekasi Timur. Cocok untuk keluarga muda.',
                'fasilitas' => [1, 4], // Taman, Masjid
            ],
            [
                'nama' => 'The Pinnacle Apartment',
                'lokasi' => 'Jakarta Pusat',
                'harga' => 3500000000,
                'luas_tanah' => 0,
                'luas_bangunan' => 120,
                'kamar_tidur' => 3,
                'kamar_mandi' => 2,
                'tipe' => 'Apartemen',
                'deskripsi' => 'Apartemen premium di jantung kota Jakarta. Fasilitas mewah dan lokasi strategis.',
                'fasilitas' => [0, 3, 6, 7], // Kolam, Keamanan, Gym, Rooftop
            ],
            [
                'nama' => 'Rumah Sahid Sudirman',
                'lokasi' => 'Surabaya',
                'harga' => 780000000,
                'luas_tanah' => 100,
                'luas_bangunan' => 80,
                'kamar_tidur' => 3,
                'kamar_mandi' => 2,
                'tipe' => 'Rumah',
                'deskripsi' => 'Rumah modern minimalis di kawasan Sudirman Surabaya. Dekat dengan pusat kota.',
                'fasilitas' => [1, 2, 3, 4], // Taman, Garasi, Keamanan, Masjid
            ],
            [
                'nama' => 'Perumahan Bumi Serpong',
                'lokasi' => 'Tangerang Selatan',
                'harga' => 920000000,
                'luas_tanah' => 140,
                'luas_bangunan' => 100,
                'kamar_tidur' => 3,
                'kamar_mandi' => 2,
                'tipe' => 'Rumah',
                'deskripsi' => 'Perumahan premium di BSD City dengan konsep smart home. Lingkungan asri dan aman.',
                'fasilitas' => [0, 1, 2, 3, 5], // Kolam, Taman, Garasi, Keamanan, Playground
            ],
            [
                'nama' => 'Kontrakan Murah Margonda',
                'lokasi' => 'Depok',
                'harga' => 280000000,
                'luas_tanah' => 45,
                'luas_bangunan' => 36,
                'kamar_tidur' => 2,
                'kamar_mandi' => 1,
                'tipe' => 'Rumah',
                'deskripsi' => 'Rumah sederhana di kawasan Margonda Depok. Dekat kampus UI dan stasiun.',
                'fasilitas' => [4], // Masjid
            ],
        ];

        foreach ($rumahData as $data) {
            $fasilitasIndexes = $data['fasilitas'];
            unset($data['fasilitas']);

            $rumah = Rumah::create($data);

            // Attach fasilitas
            foreach ($fasilitasIndexes as $index) {
                $rumah->fasilitas()->attach($fasilitasList[$index]->id);
            }
        }
    }
}
