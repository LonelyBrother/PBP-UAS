<?php

namespace App\Services;

use App\Models\MotorListrik;
use App\Models\MotorSport;
use App\Models\MotorMatic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class MmotorService
{
    /**
     * Pilih model berdasarkan kategori.
     *
     * @param  string  $kategori  listrik|sport|matic (boleh huruf besar/kecil campur)
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function getModel(string $kategori)
    {
        // samakan jadi lowercase biar konsisten
        $kategori = Str::lower($kategori);

        return match ($kategori) {
            'listrik' => new MotorListrik(),
            'sport'   => new MotorSport(),
            'matic'   => new MotorMatic(),
            default   => new MotorMatic(), // fallback kalau kategori aneh
        };
    }

    /**
     * Ambil query builder untuk kategori tertentu.
     *
     * Contoh: MmotorService::query('sport')->where('harga', '>', 10000000)->get();
     */
    public static function query(string $kategori): Builder
    {
        return self::getModel($kategori)->newQuery();
    }

    /**
     * Simpan data baru.
     */
    public static function store(string $kategori, array $data)
    {
        return self::getModel($kategori)->create($data);
    }

    /**
     * Ambil semua data untuk kategori tertentu.
     */
    public static function all(string $kategori)
    {
        return self::query($kategori)->get();
    }

    /**
     * Cari satu data by id (404 kalau tidak ketemu).
     */
    public static function find(string $kategori, $id)
    {
        return self::query($kategori)->findOrFail($id);
    }

    /**
     * Update data by id.
     */
    public static function update(string $kategori, $id, array $data)
    {
        $model = self::find($kategori, $id);
        $model->update($data);

        return $model;
    }

    /**
     * Hapus data by id.
     */
    public static function delete(string $kategori, $id)
    {
        $model = self::find($kategori, $id);

        return $model->delete();
    }
}
