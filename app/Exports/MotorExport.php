<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Carbon;  // ADD: For date formatting (replaces dateID)
use Illuminate\Support\Facades\Config;  // ADD: For full asset URLs

class MotorExport implements FromCollection, WithHeadings, WithMapping
{
    protected $motors;

    public function __construct($motors)
    {
        $this->motors = $motors;
    }

    public function collection()
    {
        return $this->motors;
    }

    public function headings(): array
    {
        return [
            'Nama Motor',
            'Brand',
            'Kategori',
            'Harga',
            'Kenyamanan',
            'Perawatan',
            'Tanggal Import',
            'Gambar URL'
        ];
    }

    public function map($motor): array
    {
        // Format date with Carbon (dd-mm-yyyy, fallback to '-' if null)
        $tanggalImport = $motor->tanggal_import ? Carbon::parse($motor->tanggal_import)->format('d-m-Y') : '-';

        // Full URL for image (using config('app.url') for export context)
        $gambarUrl = $motor->gambar 
            ? rtrim(Config::get('app.url'), '/') . '/storage/' . $motor->gambar 
            : 'Tidak ada';

        return [
            $motor->nama_motor,
            $motor->brand,
            $motor->kategori,
            $motor->harga,
            $motor->kenyamanan,
            $motor->perawatan,
            $tanggalImport,
            $gambarUrl,
        ];
    }
}