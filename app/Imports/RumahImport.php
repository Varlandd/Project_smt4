<?php

namespace App\Imports;

use App\Models\Rumah;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RumahImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Pengecekan agar baris kosong tidak ter-insert
            if (!isset($row['nama']) || empty(trim($row['nama']))) {
                continue;
            }

            Rumah::create([
                'nama'          => $row['nama'],
                'lokasi'        => $row['lokasi'] ?? null,
                'kota'          => $row['kota'] ?? null,     
                'area'          => $row['area'] ?? null,
                'posisi_kota'   => $row['posisi_kota'] ?? null,
                'harga'         => isset($row['harga']) ? (int)$row['harga'] : null,
                'luas_tanah'    => isset($row['luas_tanah']) ? (int)$row['luas_tanah'] : null,
                'luas_bangunan' => isset($row['luas_bangunan']) ? (int)$row['luas_bangunan'] : null,
                'kamar_tidur'   => isset($row['kamar_tidur']) ? (int)$row['kamar_tidur'] : null,
                'kamar_mandi'   => isset($row['kamar_mandi']) ? (int)$row['kamar_mandi'] : null,
                'harga_per_m2_tanah'    => isset($row['harga_per_m2_tanah']) ? (float)$row['harga_per_m2_tanah'] : null,
                'harga_per_m2_bangunan' => isset($row['harga_per_m2_bangunan']) ? (float)$row['harga_per_m2_bangunan'] : null,
                'cluster_harga' => isset($row['cluster_harga']) ? (int)$row['cluster_harga'] : null,
                'kategori_harga'=> $row['kategori_harga'] ?? null,
                'tipe'          => $row['tipe'] ?? 'jual',
                'deskripsi'     => $row['deskripsi'] ?? null,
                'foto' => isset($row['foto']) ? array_map('trim', explode(',', $row['foto'])) : [],
                'latitude'      => isset($row['latitude']) ? (float)$row['latitude'] : null,
                'longitude'     => isset($row['longitude']) ? (float)$row['longitude'] : null,
            ]);
        }
    }
}

