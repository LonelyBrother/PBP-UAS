<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Services\MmotorService;

class Ctopsis extends Controller
{
    /**
     * TOPSIS dengan bobot default.
     * Route: GET /topsis
     */
    public function topsis()
    {
        $weights = [
            'harga'      => 0.25,
            'kenyamanan' => 0.40,
            'perawatan'  => 0.35,
        ];

        $types = [
            'harga'      => 'cost',
            'kenyamanan' => 'benefit',
            'perawatan'  => 'benefit',
        ];

        $hasil = [];

        foreach (['listrik', 'matic', 'sport'] as $kategori) {
            $motors = MmotorService::all($kategori);

            if ($motors->isNotEmpty()) {
                $hasil[$kategori] = $this->hitungTopsisKategori(
                    $motors, $weights, $types
                );
            }
        }

        return view('topsis.motor_result', [
            'hasil' => $hasil,
            'bobot' => $weights,
        ]);
    }

    /**
     * Form input bobot (user pilih bobot sendiri).
     * Route: GET /topsis/form
     */
    public function formBobot()
    {
        return view('topsis.input_bobot');
    }

    /**
     * Hitung TOPSIS dengan bobot dari user.
     * Route: POST /topsis/hitung
     */
    public function hitungTopsis(Request $request)
    {
        $request->validate([
            'harga'      => 'required|numeric|between:0,100',
            'kenyamanan' => 'required|numeric|between:0,100',
            'perawatan'  => 'required|numeric|between:0,100',
        ]);

        $totalBobot = $request->harga + $request->kenyamanan + $request->perawatan;

        if ($totalBobot != 100) {
            return back()->with('error', 'Total bobot harus 100%. Saat ini: ' . $totalBobot . '%');
        }

        $bobot = [
            'harga'      => $request->harga / 100,
            'kenyamanan' => $request->kenyamanan / 100,
            'perawatan'  => $request->perawatan / 100,
        ];

        $types = [
            'harga'      => 'cost',
            'kenyamanan' => 'benefit',
            'perawatan'  => 'benefit',
        ];

        // Ambil semua motor dari 3 kategori
        $motors = MmotorService::all('listrik')
            ->concat(MmotorService::all('sport'))
            ->concat(MmotorService::all('matic'));

        if ($motors->isEmpty()) {
            return back()->with('error', 'Tidak ada data motor untuk dihitung TOPSIS.');
        }

        // Normalisasi awal
        $pembagi = [
            'harga'      => sqrt($motors->sum(fn($m) => pow($m->harga, 2))),
            'kenyamanan' => sqrt($motors->sum(fn($m) => pow($m->kenyamanan, 2))),
            'perawatan'  => sqrt($motors->sum(fn($m) => pow($m->perawatan, 2))),
        ];

        $normalisasi = $motors->map(function ($m) use ($pembagi) {
            return [
                'id'         => $m->id,
                'nama_motor' => $m->nama_motor,
                'brand'      => $m->brand,
                'kategori'   => $m->kategori,
                'harga'      => $m->harga / $pembagi['harga'],
                'kenyamanan' => $m->kenyamanan / $pembagi['kenyamanan'],
                'perawatan'  => $m->perawatan / $pembagi['perawatan'],
            ];
        });

        // Terbobot
        $terbobot = $normalisasi->map(function ($row) use ($bobot) {
            return [
                ...$row,
                'harga'      => $row['harga'] * $bobot['harga'],
                'kenyamanan' => $row['kenyamanan'] * $bobot['kenyamanan'],
                'perawatan'  => $row['perawatan'] * $bobot['perawatan'],
            ];
        });

        // Ideal plus/minus
        [$idealPlus, $idealMinus] = $this->hitungIdeal($terbobot->toArray(), $types);

        // Hitung ranking
        $ranking = $terbobot->map(function ($row) use ($idealPlus, $idealMinus, $motors) {
            $m = $motors->firstWhere('id', $row['id']);

            $dPlus = sqrt(
                pow($row['harga']      - $idealPlus['harga'], 2) +
                pow($row['kenyamanan'] - $idealPlus['kenyamanan'], 2) +
                pow($row['perawatan']  - $idealPlus['perawatan'], 2)
            );

            $dMinus = sqrt(
                pow($row['harga']      - $idealMinus['harga'], 2) +
                pow($row['kenyamanan'] - $idealMinus['kenyamanan'], 2) +
                pow($row['perawatan']  - $idealMinus['perawatan'], 2)
            );

            $nilai = $dMinus / ($dPlus + $dMinus);

            return [
                ...$row,

                // 🔥 DATA ASLI (BUAT DETAIL VIEW)
                'harga_asli'      => $m->harga,
                'kenyamanan_asli' => $m->kenyamanan,
                'perawatan_asli'  => $m->perawatan,

                'nilai' => round($nilai, 4),
            ];
        })->sortByDesc('nilai')->values();

        // simpan ke session buat chart & export pdf
        session(['ranking' => $ranking, 'bobot' => $bobot]);

        // optional: file debug
        file_put_contents(storage_path('app/debug_topsis_manual.json'), json_encode([
            'bobot'       => $bobot,
            'pembagi'     => $pembagi,
            'normalisasi' => $normalisasi,
            'terbobot'    => $terbobot,
            'idealPlus'   => $idealPlus,
            'idealMinus'  => $idealMinus,
            'ranking'     => $ranking,
        ], JSON_PRETTY_PRINT));

        file_put_contents(
            storage_path('app/kenyamanan_values.json'),
            json_encode($motors->pluck('kenyamanan'), JSON_PRETTY_PRINT)
        );

        // ===============================
        // KELOMPOKKAN HASIL PER KATEGORI
        // ===============================
        $hasil = $ranking->groupBy('kategori');

        // simpan ke session
        session([
            'hasil' => $hasil,
            'bobot' => $bobot
        ]);

        return view('topsis.motor_result', compact('hasil', 'bobot'));
    }

    /**
     * Chart dari hasil ranking (pakai data di session).
     */
    public function chart()
    {
        $ranking = session('ranking');
        $bobot   = session('bobot');

        if (!$ranking) {
            return redirect()->route('topsis.formBobot')->with('error', 'Data ranking tidak ditemukan.');
        }

        return view('topsis.chart', ['ranking' => $ranking, 'bobot' => $bobot]);
    }

    /**
     * Export PDF dari ranking yang sudah dihitung.
     */
    public function exportPdf()
    {
        $ranking = collect(session('ranking')); // ⬅️ PENTING
        $bobot   = session('bobot');

        if ($ranking->isEmpty()) {
            return redirect()->route('topsis.formBobot')
                ->with('error', 'Data ranking tidak ditemukan.');
        }

        // 🔥 GROUPING DI CONTROLLER (BUKAN DI BLADE)
        $rankingPerKategori = $ranking->groupBy('kategori');

        $pdf = Pdf::loadView(
            'topsis.export_pdf',
            compact('rankingPerKategori', 'bobot')
        )->setPaper('a4', 'portrait');

        return $pdf->download('hasil_ranking_topsis.pdf');
    }

    /**
     * Hitung solusi ideal positif & negatif.
     */
    private function hitungIdeal(array $data, array $types)
    {
        $collection = collect($data);

        $idealPlus  = [];
        $idealMinus = [];

        foreach ($types as $key => $type) {
            $values = $collection->pluck($key);
            $idealPlus[$key]  = $type === 'benefit' ? $values->max() : $values->min();
            $idealMinus[$key] = $type === 'benefit' ? $values->min() : $values->max();
        }

        return [$idealPlus, $idealMinus];
    }

    private function hitungTopsisKategori($motors, $weights, $types)
    {
        // ==== MATRIX (harga di-log) ====
        $matrix = [];
        foreach ($motors as $m) {
            $matrix[$m->id] = [
                'harga'      => log($m->harga), // 🔥 REDAM DOMINASI
                'kenyamanan' => $m->kenyamanan,
                'perawatan'  => $m->perawatan,
            ];
        }

        // ==== NORMALISASI ====
        $normal = [];
        foreach ($weights as $key => $w) {
            $denominator = sqrt(array_sum(
                array_map(fn($row) => pow($row[$key], 2), $matrix)
            ));

            foreach ($matrix as $id => $row) {
                $normal[$id][$key] = $row[$key] / $denominator;
            }
        }

        // ==== TERBOBOT ====
        $weighted = [];
        foreach ($normal as $id => $row) {
            foreach ($row as $key => $val) {
                $weighted[$id][$key] = $val * $weights[$key];
            }
        }

        // ==== IDEAL ====
        [$idealPlus, $idealMinus] = $this->hitungIdeal($weighted, $types);

        // ==== PREFERENSI ====
        $preferences = [];
        foreach ($weighted as $id => $row) {
            $dPlus = $dMinus = 0;
            foreach ($row as $key => $val) {
                $dPlus  += pow($val - $idealPlus[$key], 2);
                $dMinus += pow($val - $idealMinus[$key], 2);
            }

            $preferences[$id] = sqrt($dMinus) / (sqrt($dPlus) + sqrt($dMinus));
        }

        arsort($preferences);

        return collect($preferences)->map(function ($nilai, $id) use ($motors) {$m = $motors->firstWhere('id', $id);

        return [
            'id'         => $id,
            'nama_motor' => $m->nama_motor,
            'brand'      => $m->brand,
            'kategori'   => $m->kategori,

            // 🔥 DATA ASLI MOTOR (UNTUK DETAIL)
            'harga_asli'      => $m->harga,
            'kenyamanan_asli' => $m->kenyamanan,
            'perawatan_asli'  => $m->perawatan,

            // 🔹 HASIL TOPSIS
            'nilai' => round($nilai, 4),
        ];
        })->values();
    }
}
