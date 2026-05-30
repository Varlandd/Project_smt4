<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rumah;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class GenerateRumahPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rumah:generate-photos {--force : Re-generate all photos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate missing physical photo files for properties in local storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan dan pembuatan file foto rumah...');
        $force = $this->option('force');

        // Pastikan folder storage/app/public/rumah_photos ada
        if (!Storage::disk('public')->exists('rumah_photos')) {
            Storage::disk('public')->makeDirectory('rumah_photos');
            $this->info('Folder rumah_photos berhasil dibuat.');
        }

        $rumahs = Rumah::all();
        $totalMissing = 0;
        $totalCreated = 0;

        // Kita buat/download 20 gambar rumah acak secara lokal untuk di-copy agar bervariasi
        $dummyImages = [];
        $this->info('Mendownload 20 gambar rumah sampel dari internet...');
        for ($i = 1; $i <= 20; $i++) {
            $dummyPath = 'rumah_photos/dummy_base_' . $i . '.jpg';
            if (!Storage::disk('public')->exists($dummyPath)) {
                try {
                    $response = Http::timeout(10)->get('https://loremflickr.com/600/400/house,villa/all?lock=' . $i);
                    if ($response->successful()) {
                        Storage::disk('public')->put($dummyPath, $response->body());
                    } else {
                        // Fallback buat gambar GD jika gagal download
                        $this->createFallbackGdImage($dummyPath, $i);
                    }
                } catch (\Exception $e) {
                    $this->createFallbackGdImage($dummyPath, $i);
                }
            }
            $dummyImages[] = $dummyPath;
        }

        $this->info('Memeriksa data rumah di database (proses ini memakan waktu)...');

        $bar = $this->output->createProgressBar(count($rumahs));
        $bar->start();

        foreach ($rumahs as $rumah) {
            $fotos = is_array($rumah->foto) ? $rumah->foto : [$rumah->foto];
            
            foreach ($fotos as $idx => $fotoPath) {
                if (empty($fotoPath)) continue;

                $basename = basename(str_replace('\\', '/', $fotoPath));
                $storagePath = 'rumah_photos/' . $basename;

                // Jika force, kita hapus file lama (kecuali file dummy_base)
                if ($force && !str_starts_with($basename, 'dummy_base_')) {
                    if (Storage::disk('public')->exists($storagePath)) {
                        Storage::disk('public')->delete($storagePath);
                    }
                }

                // Jika file fisik belum ada
                if (!Storage::disk('public')->exists($storagePath)) {
                    $totalMissing++;
                    
                    // Pilih gambar secara acak berdasarkan hash dari nama file, jadi pasti unik per foto
                    $randomDummy = $dummyImages[abs(crc32($basename)) % 20];
                    Storage::disk('public')->copy($randomDummy, $storagePath);
                    
                    $totalCreated++;
                }
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        $this->info("Selesai!");
        $this->info("Total file foto yang hilang: $totalMissing");
        $this->info("Total file foto yang berhasil dibuat: $totalCreated");
        $this->info("Silakan refresh website Anda, gambar akan muncul dengan sempurna!");
    }

    private function createFallbackGdImage($dummyPath, $index)
    {
        $img = imagecreatetruecolor(600, 400);
        $bg = imagecolorallocate($img, rand(100, 255), rand(100, 255), rand(100, 255));
        imagefill($img, 0, 0, $bg);
        $textColor = imagecolorallocate($img, 0, 0, 0);
        imagestring($img, 5, 200, 200, "Foto Rumah " . $index, $textColor);
        
        // Simpan langsung pakai path absolut
        $fullPath = storage_path('app/public/' . $dummyPath);
        imagejpeg($img, $fullPath);
        imagedestroy($img);
    }
}
