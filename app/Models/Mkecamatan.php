<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mkecamatan extends Model
{
    protected $table = 'kecamatan';
    public $timestamps = false;

    protected $fillable = ['id', 'kabupaten_id', 'nama'];

    public $incrementing = false;
    protected $keyType = 'int';

    public function kabupaten()
    {
        return $this->belongsTo(Mkabupaten::class, 'kabupaten_id');
    }
}
