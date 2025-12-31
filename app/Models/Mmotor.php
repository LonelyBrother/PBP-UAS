<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class Mmotor extends Model   // <- WAJIB abstract
{
    use HasFactory;

    protected $fillable = [
        'nama_motor',
        'brand',
        'kategori',
        'harga',
        'kenyamanan',
        'perawatan',
        'gambar',
        'tanggal_import',
    ];
}
