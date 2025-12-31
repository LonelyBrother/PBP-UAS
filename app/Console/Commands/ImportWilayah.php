<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use App\Models\Mprovinsi;
use App\Models\Mkabupaten;
use App\Models\Mkecamatan;

class ImportWilayah extends Command
{
    protected $signature = 'import:wilayah';
    protected $description = 'Import data provinsi, kabupaten, kecamatan dari API Indonesia';

    public function handle()
    {
        // ========================
        // 1. IMPORT PROVINSI
        // ========================
        $this->info("Mengambil data provinsi...");
        $provinsi = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")->json();

        foreach ($provinsi as $p) {
            Mprovinsi::updateOrCreate(
                ['id' => $p['id']],
                ['nama' => $p['name']]
            );
        }

        $this->info("Provinsi selesai!");

        // ========================
        // 2. IMPORT KABUPATEN
        // ========================
        $this->info("Mengambil data kabupaten...");
        foreach ($provinsi as $p) {
            $kabupaten = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$p['id']}.json")->json();

            foreach ($kabupaten as $k) {
                Mkabupaten::updateOrCreate(
                    ['id' => $k['id']],
                    [
                        'provinsi_id' => $p['id'],
                        'nama' => $k['name'],
                    ]
                );
            }
        }

        $this->info("Kabupaten selesai!");

        // ========================
        // 3. IMPORT KECAMATAN
        // ========================
        $this->info("Mengambil data kecamatan...");
        $allKabupaten = Mkabupaten::all();

        foreach ($allKabupaten as $kab) {
            $kecamatan = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$kab->id}.json")->json();

            foreach ($kecamatan as $c) {
                Mkecamatan::updateOrCreate(
                    ['id' => $c['id']],
                    [
                        'kabupaten_id' => $kab->id,
                        'nama' => $c['name'],
                    ]
                );
            }
        }

        $this->info("Import wilayah selesai!");
    }
}
