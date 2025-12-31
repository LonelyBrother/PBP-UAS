<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mmotor;

class Mpelunasan extends Model
{
    use HasFactory;

    protected $table = 'pelunasan';

    protected $fillable = [
        'no_pelunasan',
        'pembeli_id',
        'status',
        'tanggal_pelunasan',
        'daftar_motor',
    ];

    protected $casts = [
        'daftar_motor'      => 'array',
        'tanggal_pelunasan' => 'date',
    ];

    /**
     * Defensive accessor to ensure daftar_motor always returns an array.
     * Handles cases where stored value may be boolean, null, string, or JSON.
     */
    public function getDaftarMotorAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_null($value) || is_bool($value)) {
            return [];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }

            // fallback: comma separated
            $parts = array_filter(array_map('trim', explode(',', $value)));
            return array_values($parts);
        }

        return [];
    }

    /**
     * Helper accessor: ambil daftar nama motor sebagai array.
     */
    public function getDaftarMotorListAttribute()
    {
        // kalau sudah array (JSON)
        if (is_array($this->daftar_motor)) {
            return $this->daftar_motor;
        }

        // kalau teks biasa (dipisahkan koma)
        return explode(',', $this->daftar_motor);
    }

    /**
     * Helper untuk ambil data motor dari tabel motor berdasarkan nama.
     */
    public function motorData()
    {
        return Mmotor::whereIn('nama_motor', $this->daftar_motor_list)->get();
    }

    public function pembeli()
    {
        return $this->belongsTo(Mpembeli::class, 'pembeli_id');
    }
}
