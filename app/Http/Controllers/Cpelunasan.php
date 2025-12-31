<?php

namespace App\Http\Controllers;

use App\Models\Mpelunasan;
use App\Services\MmotorService;
use App\Models\Mpembeli;
use Illuminate\Http\Request;

class Cpelunasan extends Controller
{
    public function index(Request $request)
    {
        $query = Mpelunasan::with('pembeli');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('no_pelunasan', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('tanggal_pelunasan', 'like', "%{$search}%");
            })->orWhereHas('pembeli', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        $pelunasan = $query->orderBy('no_pelunasan', 'asc')->paginate(10);

        return view('pelunasan.index', compact('pelunasan'));
    }

    public function create()
    {
        $motor = MmotorService::all('listrik')
            ->concat(MmotorService::all('matic'))
            ->concat(MmotorService::all('sport'));

        $pembeli = Mpembeli::orderBy('nama')->get();

        return view('pelunasan.create', [
            'pembeli' => $pembeli,
            'motor'   => $motor,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pelunasan'      => 'required|string|max:20|unique:pelunasan,no_pelunasan',
            'pembeli_id'        => 'required|exists:pembeli,id',
            'status'            => 'required|in:Lunas,Belum Lunas',
            'tanggal_pelunasan' => 'required|date',
            'daftar_motor'      => 'required|array|min:1',
        ]);

        Mpelunasan::create([
            'no_pelunasan'      => $request->no_pelunasan,
            'pembeli_id'        => $request->pembeli_id,
            'status'            => $request->status,
            'tanggal_pelunasan' => $request->tanggal_pelunasan,
            'daftar_motor'      => $request->daftar_motor, // otomatis di-cast ke array
        ]);

        return redirect()->route('pelunasan.index')
            ->with('success', 'Data pelunasan berhasil disimpan!');
    }

    public function edit($id)
    {
        $pelunasan = Mpelunasan::findOrFail($id);

        $motor = MmotorService::all('listrik')
            ->concat(MmotorService::all('matic'))
            ->concat(MmotorService::all('sport'));

        $pembeli = Mpembeli::orderBy('nama')->get();

        return view('pelunasan.edit', compact('pelunasan', 'motor', 'pembeli'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pembeli_id'        => 'required|exists:pembeli,id',
            'status'            => 'required|in:Lunas,Belum Lunas',
            'tanggal_pelunasan' => 'required|date',
            'daftar_motor'      => 'required|array|min:1',
        ]);

        $pelunasan = Mpelunasan::findOrFail($id);

        $pelunasan->update([
            'pembeli_id'        => $request->pembeli_id,
            'status'            => $request->status,
            'tanggal_pelunasan' => $request->tanggal_pelunasan,
            'daftar_motor'      => $request->daftar_motor,
        ]);

        return redirect()->route('pelunasan.index')
            ->with('success', 'Data pelunasan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pelunasan = Mpelunasan::findOrFail($id);
        $pelunasan->delete();

        return redirect()->route('pelunasan.index')
            ->with('success', 'Data pelunasan berhasil dihapus!');
    }

    public function destroyAll()
    {
        Mpelunasan::truncate();

        return redirect()->route('pelunasan.index')
            ->with('success', 'Semua data pelunasan berhasil dihapus!');
    }

    public function show($id)
    {
        $pelunasan = Mpelunasan::with('pembeli')->findOrFail($id);
        return view('pelunasan.show', compact('pelunasan'));
    }
}
