<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rumah;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;

class UpdateKoordinat extends Command
{
    protected $signature = 'rumah:update-koordinat {file}';
    protected $description = 'Update latitude dan longitude dari file Excel';

    public function handle()
    {
        $path = $this->argument('file');

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: $path");
            return;
        }

        $rows = Excel::toCollection(null, $path)->first();
        $header = $rows->first()->toArray();
        $rows->shift(); // hapus baris header

        $updated = 0;
        $notFound = 0;

        foreach ($rows as $row) {
            $data = array_combine($header, $row->toArray());

            $nama = trim($data['nama'] ?? '');
            $lat  = $data['latitude'] ?? null;
            $lng  = $data['longitude'] ?? null;

            if (empty($nama) || is_null($lat) || is_null($lng)) continue;

            $rumah = Rumah::where('nama', $nama)->first();

            if ($rumah) {
                $rumah->update([
                    'latitude'  => (float) $lat,
                    'longitude' => (float) $lng,
                ]);
                $updated++;
            } else {
                $notFound++;
                $this->warn("Tidak ditemukan: $nama");
            }
        }

        $this->info("Selesai! $updated data diupdate, $notFound tidak ditemukan.");
    }
}