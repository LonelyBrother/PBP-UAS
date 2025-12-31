<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpembeli extends Model
{
    use HasFactory;

    protected $table = 'pembeli';

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'provinsi_id',
        'provinsi_nama',
        'kabupaten_id',
        'kabupaten_nama',
        'kecamatan_id',
        'kecamatan_nama',
    ];

    public function pelunasan()
    {
        return $this->hasMany(Mpelunasan::class, 'pembeli_id');
    }
}
