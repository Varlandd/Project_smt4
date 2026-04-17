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
            'Nama',
            'Lokasi',
            'Harga',
            'Luas Tanah',
            'Luas Bangunan',
            'Kamar Tidur',
            'Kamar Mandi',
            'Tipe',
            'Deskripsi',
        ];
    }

    public function map($rumah): array
    {
        return [
            $rumah->nama,
            $rumah->lokasi,
            $rumah->harga,
            $rumah->luas_tanah,
            $rumah->luas_bangunan,
            $rumah->kamar_tidur,
            $rumah->kamar_mandi,
            $rumah->tipe,
            $rumah->deskripsi,
        ];
    }
}
