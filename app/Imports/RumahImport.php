<?php

namespace App\Imports;

use App\Models\Rumah;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RumahImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new RumahSheetImport(),
        ];
    }
}

class RumahSheetImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index < 3) continue;
            if (empty($row[0]) || trim($row[0]) === '') continue;

            Rumah::create([
                'nama'                  => $row[0]  ?? null,
                'lokasi'                => $row[1]  ?? null,
                'kota'                  => $row[2]  ?? null,
                'area'                  => $row[3]  ?? null,
                'latitude'              => isset($row[4])  ? (float)$row[4]  : null,
                'longitude'             => isset($row[5])  ? (float)$row[5]  : null,
                'posisi_kota'           => $row[6]  ?? null,
                'harga'                 => isset($row[7])  ? (int)$row[7]    : null,
                'luas_tanah'            => isset($row[8])  ? (int)$row[8]    : null,
                'luas_bangunan'         => isset($row[9])  ? (int)$row[9]    : null,
                'kamar_tidur'           => isset($row[10]) ? (int)$row[10]   : null,
                'kamar_mandi'           => isset($row[11]) ? (int)$row[11]   : null,
                'harga_per_m2_tanah'    => isset($row[12]) ? (float)$row[12] : null,
                'harga_per_m2_bangunan' => isset($row[13]) ? (float)$row[13] : null,
                'cluster_harga'         => isset($row[14]) ? (int)$row[14]   : null,
                'kategori_harga'        => $row[15] ?? null,
                'deskripsi'             => $row[16] ?? null,
                'foto'                  => isset($row[17]) ? array_map('trim', explode(',', $row[17])) : [],
                'tipe'                  => 'jual',
            ]);
        }
    }
}