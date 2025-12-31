<?php

namespace App\Http\Controllers;

use App\Exports\MotorExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\MmotorService;

class Cmotor extends Controller
{
    /* ======================================================
     * INDEX / LIST
     * ====================================================== */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'listrik');
        $search   = $request->get('search');

        // ===== ALL KATEGORI =====
        if ($kategori === 'all') {
            $allMotors = collect()
                ->concat(MmotorService::all('listrik'))
                ->concat(MmotorService::all('matic'))
                ->concat(MmotorService::all('sport'));

            if ($search) {
                $allMotors = $allMotors->filter(fn ($m) =>
                    str_contains(strtolower($m->nama_motor), strtolower($search)) ||
                    str_contains(strtolower($m->brand), strtolower($search))
                );
            }

            // manual pagination
            $page    = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $items   = $allMotors->slice(($page - 1) * $perPage, $perPage)->values();

            $motors = new LengthAwarePaginator(
                $items,
                $allMotors->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('motor.index', compact('motors', 'kategori', 'search'));
        }

        // ===== PER KATEGORI =====
        $query = MmotorService::query($kategori);

        if ($search) {
            $query->where(fn ($q) =>
                $q->where('nama_motor', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
            );
        }

        $motors = $query->orderByDesc('created_at')->paginate(10);

        return view('motor.index', compact('motors', 'kategori', 'search'));
    }

    /* ======================================================
     * CREATE
     * ====================================================== */
    public function create()
    {
        return view('motor.create');
    }

    /* ======================================================
     * STORE
     * ====================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'nama_motor' => 'required|string|max:255',
            'brand'      => 'required|string|max:255',
            'kategori'   => 'required|in:listrik,matic,sport',
            'harga'      => 'required|numeric',
            'kenyamanan' => 'required|numeric|between:1,10',
            'perawatan'  => 'required|numeric|between:1,10',
            'gambar'     => 'required|image|max:15360',
        ]);

        $kategori = strtolower($request->kategori);

        $data = $request->all();
        $data['tanggal_import'] = Carbon::now();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('gambar_motor', 'public');
        }

        MmotorService::store($kategori, $data);

        return redirect()
            ->route('motor.index', ['kategori' => $kategori])
            ->with('success', 'Data motor berhasil ditambahkan.');
    }

    /* ======================================================
     * SHOW (FIXED)
     * ====================================================== */
    public function show($id)
    {
        $kategori = request('kategori'); // ambil dari query string

        if (!$kategori) {
            abort(404, 'Kategori tidak ditemukan');
        }

        $motor = MmotorService::find($kategori, $id);

        return view('motor.show', compact('motor', 'kategori'));
    }

    /* ======================================================
     * EDIT (FIXED)
     * ====================================================== */
    public function edit(Request $request, int $id)
    {
        $kategori = $request->get('kategori');

        if (!$kategori) {
            abort(404, 'Kategori wajib ada');
        }

        $motor = MmotorService::find($kategori, $id);

        return view('motor.edit', compact('motor', 'kategori'));
    }

    /* ======================================================
     * UPDATE (FIXED)
     * ====================================================== */
    public function update(Request $request, int $id)
    {
        $kategori = $request->get('kategori');

        if (!$kategori) {
            abort(404, 'Kategori wajib ada');
        }

        $motor = MmotorService::find($kategori, $id);

        $data = $request->validate([
            'nama_motor' => 'nullable|string|max:255',
            'brand'      => 'nullable|string|max:255',
            'harga'      => 'nullable|numeric',
            'kenyamanan' => 'nullable|numeric|between:1,10',
            'perawatan'  => 'nullable|numeric|between:1,10',
            'gambar'     => 'nullable|image|max:15360',
        ]);

        if ($request->hasFile('gambar')) {
            if ($motor->gambar && Storage::disk('public')->exists($motor->gambar)) {
                Storage::disk('public')->delete($motor->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('gambar_motor', 'public');
        }

        MmotorService::update($kategori, $id, $data);

        return redirect()
            ->route('motor.index', ['kategori' => $kategori])
            ->with('success', 'Data motor berhasil diperbarui.');
    }

    /* ======================================================
     * DESTROY (FIXED)
     * ====================================================== */
    public function destroy(Request $request, $id)
    {
        // cegah destroy-all nyasar ke sini
        if (!is_numeric($id)) {
            abort(404);
        }

        $kategori = $request->query('kategori');

        if (!$kategori) {
            abort(404, 'Kategori wajib');
        }

        $id = (int) $id;

        $motor = MmotorService::find($kategori, $id);

        if ($motor->gambar && Storage::disk('public')->exists($motor->gambar)) {
            Storage::disk('public')->delete($motor->gambar);
        }

        MmotorService::delete($kategori, $id);

        return redirect()
            ->route('motor.index', ['kategori' => $kategori])
            ->with('success', 'Data motor berhasil dihapus.');
    }

    /* ======================================================
     * DESTROY ALL
     * ====================================================== */
    public function destroyAll(Request $request)
    {
        $kategori = $request->get('kategori');

        if (!$kategori || $kategori === 'all') {
            return back()->with('error', 'Pilih kategori spesifik untuk hapus semua.');
        }

        $motors = MmotorService::all($kategori);

        foreach ($motors as $motor) {
            if ($motor->gambar && Storage::disk('public')->exists($motor->gambar)) {
                Storage::disk('public')->delete($motor->gambar);
            }
        }

        MmotorService::query($kategori)->delete();

        return redirect()
            ->route('motor.index', ['kategori' => $kategori])
            ->with('success', 'Semua data motor kategori ini berhasil dihapus.');
    }

    // 📥 IMPORT EXCEL
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls|max:2048']);

        try {
            $data = Excel::toArray([], $request->file('file'))[0];
            $importedCount = 0;

            foreach ($data as $i => $row) {
                if ($i === 0 || empty($row[0])) continue;

                // NOTE: pastikan isi Excel konsisten: listrik/sport/matic (lowercase)
                $kategori = strtolower($row[2] ?? 'matic');

                MmotorService::store($kategori, [
                    'nama_motor'      => $row[0] ?? '',
                    'brand'           => $row[1] ?? '',
                    'kategori'        => $kategori,
                    'harga'           => (float) ($row[3] ?? 0),
                    'kenyamanan'      => (int) ($row[4] ?? 0),
                    'perawatan'       => (int) ($row[5] ?? 0),
                    'gambar'          => $row[6] ?? null,
                    'tanggal_import'  => Carbon::now(),
                ]);

                $importedCount++;
            }

            // kalau mau, bisa redirect ke kategori tertentu, tapi ini default ke listrik
            return redirect()->route('motor.index', ['kategori' => 'listrik'])
                ->with('success', "Berhasil mengimpor {$importedCount} data dari Excel. Tanggal import diset otomatis.");
        } catch (\Exception $e) {
            return redirect()->route('motor.index', ['kategori' => 'listrik'])
                ->with('error', 'Gagal mengimpor: ' . $e->getMessage());
        }
    }

    // 📤 EXPORT EXCEL (per kategori)
    public function exportExcel(Request $request)
    {
        $kategori = $request->get('kategori', 'all');
        $search   = $request->get('search');

        if ($kategori === 'all') {
            // ambil semua kategori
            $motors = MmotorService::all('listrik')
                ->concat(MmotorService::all('matic'))
                ->concat(MmotorService::all('sport'));

            // filter search kalau ada
            if ($search) {
                $motors = $motors->filter(function ($m) use ($search) {
                    return str_contains(strtolower($m->nama_motor), strtolower($search))
                        || str_contains(strtolower($m->brand), strtolower($search));
                });
            }

            $filename = 'data_motor_semua_kategori_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        } else {
            // hanya 1 kategori
            $query = MmotorService::query($kategori);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_motor', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
                });
            }

            $motors   = $query->get();
            $filename = 'data_motor_' . $kategori . '_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        }

        return Excel::download(new MotorExport($motors), $filename);
    }

    // 📄 EXPORT PDF (gabung semua kategori)
    public function exportPdf(Request $request)
    {
        $kategori = $request->get('kategori', 'all');
        $search   = $request->get('search');

        if ($kategori === 'all') {
            $motors = MmotorService::all('listrik')
                ->concat(MmotorService::all('matic'))
                ->concat(MmotorService::all('sport'));

            if ($search) {
                $motors = $motors->filter(function ($m) use ($search) {
                    return str_contains(strtolower($m->nama_motor), strtolower($search))
                        || str_contains(strtolower($m->brand), strtolower($search));
                });
            }

            $filenameSuffix = 'semua_kategori';
        } else {
            $query = MmotorService::query($kategori);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_motor', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
                });
            }

            $motors         = $query->get();
            $filenameSuffix = $kategori;
        }

        $pdf = Pdf::loadView('motor.export', compact('motors', 'kategori', 'search'));

        return $pdf->download('data_motor_' . $filenameSuffix . '_' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    // 🖨️ CETAK
    public function cetak(Request $request)
    {
        // =====================
        // AMBIL PARAMETER
        // =====================
        $kategori = $request->get('kategori', 'all');
        $search   = $request->get('search');
        $dari     = $request->get('dari');
        $sampai   = $request->get('sampai');

        // =====================
        // VALIDASI TANGGAL
        // =====================
        if (!$dari || !$sampai) {
            return redirect()
                ->route('motor.index', ['kategori' => $kategori])
                ->with('error', 'Tanggal mulai dan selesai wajib diisi.');
        }

        // Parsing tanggal (tetap pakai Carbon, tapi nanti compare pakai whereDate)
        $dariCarbon   = Carbon::parse($dari);
        $sampaiCarbon = Carbon::parse($sampai);

        // =====================
        // FILTER QUERY (AMAN UNTUK DATETIME)
        // =====================
        $applyFilters = function ($query) use ($dariCarbon, $sampaiCarbon, $search) {

            // 🔥 FIX UTAMA: DATETIME vs DATE
            $query->whereNotNull('tanggal_import')
                ->whereDate('tanggal_import', '>=', $dariCarbon->toDateString())
                ->whereDate('tanggal_import', '<=', $sampaiCarbon->toDateString());

            // Search (opsional)
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_motor', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
                });
            }

            return $query;
        };

        // =====================
        // AMBIL DATA MOTOR
        // =====================
        if ($kategori === 'all') {

            // Gabung 3 kategori
            $motors = collect();

            foreach (['listrik', 'matic', 'sport'] as $kat) {
                $query  = MmotorService::query($kat);
                $result = $applyFilters($query)->get();

                $motors = $motors->concat($result);
            }

            $labelKategori = 'Semua kategori';

        } else {

            // Satu kategori
            $query  = MmotorService::query($kategori);
            $motors = $applyFilters($query)->get();

            $labelKategori = 'Kategori: ' . ucfirst($kategori);
        }

        // =====================
        // TAMPILKAN VIEW CETAK
        // =====================
        return view('motor.cetak', [
            'motors'        => $motors,
            'kategori'      => $kategori,
            'labelKategori' => $labelKategori,
            'dari'          => $dariCarbon->toDateString(),
            'sampai'        => $sampaiCarbon->toDateString(),
            'search'        => $search,
        ]);
    }
}
