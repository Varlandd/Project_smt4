<?php

namespace App\Exports;

use App\Models\Rumah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RumahExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Rumah::all();
    }

    public function headings(): array
    {
        return [
            'nama',
            'lokasi',
            'kota',
            'area',
            'posisi_kota',
            'harga',
            'luas_tanah',
            'luas_bangunan',
            'kamar_tidur',
            'kamar_mandi',
            'harga_per_m2_tanah',
            'harga_per_m2_bangunan',
            'cluster_harga',
            'kategori_harga',
            'tipe',
            'deskripsi',
            'foto',
            'latitude',
            'longitude',
        ];
    }

    public function map($rumah): array
    {
        return [
            $rumah->nama,
            $rumah->lokasi,
            $rumah->kota,
            $rumah->area,
            $rumah->posisi_kota,
            $rumah->harga,
            $rumah->luas_tanah,
            $rumah->luas_bangunan,
            $rumah->kamar_tidur,
            $rumah->kamar_mandi,
            $rumah->harga_per_m2_tanah,
            $rumah->harga_per_m2_bangunan,
            $rumah->cluster_harga,
            $rumah->kategori_harga,
            $rumah->tipe,
            $rumah->deskripsi,
            is_array($rumah->foto) ? implode(', ', $rumah->foto) : $rumah->foto,
            $rumah->latitude,
            $rumah->longitude,
        ];
    }
}
