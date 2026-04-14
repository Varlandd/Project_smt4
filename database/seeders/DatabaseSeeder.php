<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use App\Models\Rumah;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Hapus data lama di MongoDB ──
        User::raw(fn($col) => $col->deleteMany([]));
        Rumah::raw(fn($col) => $col->deleteMany([]));
        Fasilitas::raw(fn($col) => $col->deleteMany([]));

        // ── Admin User ──
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@rumahku.com',
            'password' => Hash::make('password'),
            'phone'    => '081234567899',
            'role'     => 'admin',
        ]);

        // ── User Demo ──
        User::create([
            'name'     => 'Demo User',
            'email'    => 'demo@rumahku.com',
            'password' => Hash::make('password'),
            'phone'    => '081234567890',
            'role'     => 'user',
        ]);

        // ── Fasilitas ──
        $fasilitasNames = [
            'Kolam Renang', 'Taman', 'Garasi', 'Keamanan 24 Jam',
            'Masjid Terdekat', 'Playground', 'Gym', 'Rooftop',
        ];

        foreach ($fasilitasNames as $nama) {
            Fasilitas::create(['nama' => $nama]);
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
                'fasilitas' => ['Kolam Renang', 'Taman', 'Garasi', 'Keamanan 24 Jam'],
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
                'fasilitas' => ['Kolam Renang', 'Keamanan 24 Jam', 'Gym', 'Rooftop'],
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
                'fasilitas' => ['Taman', 'Garasi', 'Keamanan 24 Jam', 'Playground'],
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
                'fasilitas' => ['Garasi', 'Keamanan 24 Jam'],
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
                'fasilitas' => ['Kolam Renang', 'Taman', 'Garasi', 'Playground'],
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
                'fasilitas' => ['Taman', 'Masjid Terdekat'],
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
                'fasilitas' => ['Kolam Renang', 'Keamanan 24 Jam', 'Gym', 'Rooftop'],
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
                'fasilitas' => ['Taman', 'Garasi', 'Keamanan 24 Jam', 'Masjid Terdekat'],
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
                'fasilitas' => ['Kolam Renang', 'Taman', 'Garasi', 'Keamanan 24 Jam', 'Playground'],
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
                'fasilitas' => ['Masjid Terdekat'],
            ],
        ];

        foreach ($rumahData as $data) {
            $data['favorited_user_ids'] = [];
            Rumah::create($data);
        }

        echo "✅ Seeding ke MongoDB berhasil!\n";
    }
}

