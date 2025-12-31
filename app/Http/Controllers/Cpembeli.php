<?php

namespace App\Http\Controllers;

use App\Models\Mpembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Cpembeli extends Controller
{
    /* =========================================================
    |  HELPER
    ========================================================= */
    private function authorizeAdmin()
    {
        abort_if(
            !auth()->check() ||
            (auth()->user()->role ?? auth()->user()->Level) !== 'admin',
            403,
            'Akses ditolak'
        );
    }

    private function getWilayahNama(string $url, ?string $id): ?string
    {
        if (!$id) return null;

        $list = Http::get($url)->json();
        $match = collect($list)->firstWhere('id', $id);

        return $match['name'] ?? null;
    }

    /* =========================================================
    |  INDEX
    ========================================================= */
    public function index(Request $request)
    {
        $search = $request->search;

        $pembeli = Mpembeli::when($search, function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('alamat', 'like', "%{$search}%")
              ->orWhere('telepon', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->paginate(10);

        return view('pembeli.index', compact('pembeli'));
    }

    /* =========================================================
    |  CREATE (ADMIN ONLY)  ✅ FIX ERROR DI SINI
    ========================================================= */
    public function create()
    {
        $this->authorizeAdmin();

        return view('pembeli.create');
    }

    /* =========================================================
    |  STORE (ADMIN ONLY)
    ========================================================= */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'nama'         => 'required|string|max:100',
            'alamat'       => 'required|string|max:255',
            'telepon'      => 'required|string|max:20',
            'provinsi_id'  => 'required',
            'kabupaten_id' => 'required',
            'kecamatan_id' => 'required',
        ]);

        $provinsiId  = $request->provinsi_id;
        $kabupatenId = $request->kabupaten_id;
        $kecamatanId = $request->kecamatan_id;

        Mpembeli::create([
            'nama'            => $request->nama,
            'alamat'          => $request->alamat,
            'telepon'         => $request->telepon,
            'provinsi_id'     => $provinsiId,
            'provinsi_nama'   => $this->getWilayahNama(
                'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
                $provinsiId
            ),
            'kabupaten_id'    => $kabupatenId,
            'kabupaten_nama'  => $this->getWilayahNama(
                "https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinsiId}.json",
                $kabupatenId
            ),
            'kecamatan_id'    => $kecamatanId,
            'kecamatan_nama'  => $this->getWilayahNama(
                "https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$kabupatenId}.json",
                $kecamatanId
            ),
        ]);

        return redirect()
            ->route('pembeli.index')
            ->with('success', 'Data pembeli berhasil disimpan!');
    }

    /* =========================================================
    |  SHOW
    ========================================================= */
    public function show($id)
    {
        $pembeli = Mpembeli::findOrFail($id);
        return view('pembeli.show', compact('pembeli'));
    }

    /* =========================================================
    |  EDIT (ADMIN ONLY)
    ========================================================= */
    public function edit($id)
    {
        $this->authorizeAdmin();

        $pembeli = Mpembeli::findOrFail($id);
        return view('pembeli.edit', compact('pembeli'));
    }

    /* =========================================================
    |  UPDATE (ADMIN ONLY)
    ========================================================= */
    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $request->validate([
            'nama'         => 'required|string|max:100',
            'alamat'       => 'required|string|max:255',
            'telepon'      => 'required|string|max:20',
            'provinsi_id'  => 'required',
            'kabupaten_id' => 'required',
            'kecamatan_id' => 'required',
        ]);

        $pembeli = Mpembeli::findOrFail($id);

        $provinsiId  = $request->provinsi_id;
        $kabupatenId = $request->kabupaten_id;
        $kecamatanId = $request->kecamatan_id;

        $pembeli->update([
            'nama'            => $request->nama,
            'alamat'          => $request->alamat,
            'telepon'         => $request->telepon,
            'provinsi_id'     => $provinsiId,
            'provinsi_nama'   => $this->getWilayahNama(
                'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
                $provinsiId
            ),
            'kabupaten_id'    => $kabupatenId,
            'kabupaten_nama'  => $this->getWilayahNama(
                "https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinsiId}.json",
                $kabupatenId
            ),
            'kecamatan_id'    => $kecamatanId,
            'kecamatan_nama'  => $this->getWilayahNama(
                "https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$kabupatenId}.json",
                $kecamatanId
            ),
        ]);

        return redirect()
            ->route('pembeli.index')
            ->with('success', 'Data pembeli berhasil diperbarui!');
    }

    /* =========================================================
    |  DESTROY (ADMIN ONLY)
    ========================================================= */
    public function destroy($id)
    {
        $this->authorizeAdmin();

        Mpembeli::findOrFail($id)->delete();

        return redirect()
            ->route('pembeli.index')
            ->with('success', 'Data pembeli berhasil dihapus!');
    }

    /* =========================================================
    |  DESTROY ALL (ADMIN ONLY)
    ========================================================= */
    public function destroyAll()
    {
        $this->authorizeAdmin();

        Mpembeli::query()->delete();

        return redirect()
            ->route('pembeli.index')
            ->with('success', 'Seluruh data pembeli berhasil dihapus!');
    }

    /* =========================================================
    |  AJAX WILAYAH
    ========================================================= */
    public function getKabupaten($provinsi_id)
    {
        return response()->json(
            array_map(
                fn ($i) => ['id' => $i['id'], 'nama' => $i['name']],
                Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$provinsi_id}.json")->json()
            )
        );
    }

    public function getKecamatan($kabupaten_id)
    {
        return response()->json(
            array_map(
                fn ($i) => ['id' => $i['id'], 'nama' => $i['name']],
                Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$kabupaten_id}.json")->json()
            )
        );
    }
}
