<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mkabupaten extends Model
{
    protected $table = 'kabupaten';
    public $timestamps = false;

    protected $fillable = ['id', 'provinsi_id', 'nama']; // <-- tambahkan 'id'

    public $incrementing = false; // <-- wajib
    protected $keyType = 'int';   // <-- wajib

    public function kecamatan()
    {
        return $this->hasMany(Mkecamatan::class, 'kabupaten_id');
    }

    public function provinsi()
    {
        return $this->belongsTo(Mprovinsi::class, 'provinsi_id');
    }
}

