<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mprovinsi extends Model
{
    protected $table = 'provinsi';
    public $timestamps = false;

    protected $fillable = ['id', 'nama']; // <-- Tambahkan 'id'

    public $incrementing = false; // <-- Penting
    protected $keyType = 'int';   // <-- Karena ID dari API adalah integer

    public function kabupaten()
    {
        return $this->hasMany(Mkabupaten::class, 'provinsi_id');
    }
}

