<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Motor</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"><!-- kalau pakai Laravel Mix/Bootstrap, biarkan saja -->

    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 12px;
            color: #333;
        }
        .cetak-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 16px;
        }
        .cetak-header {
            text-align: center;
            margin-bottom: 16px;
        }
        .cetak-header h3 {
            margin-bottom: 4px;
        }
        .cetak-meta {
            font-size: 11px;
            color: #555;
            margin-bottom: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
        }
        th {
            background: #f5f5f5;
            text-align: center;
        }
        td {
            vertical-align: middle;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .small { font-size: 10px; }

        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; }
            .cetak-container { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="cetak-container">
    <div class="cetak-header">
        <h3>Data Motor</h3>
        @php
            $labelKategori = $labelKategori
                ?? (isset($kategori)
                    ? ($kategori === 'all' ? 'Semua kategori' : 'Kategori: ' . ucfirst($kategori))
                    : 'Semua kategori');
        @endphp
        <div class="cetak-meta">
            <div>{{ $labelKategori }}</div>
            @if(isset($dari, $sampai) && $dari && $sampai)
                <div>Periode Tanggal Import:
                    <strong>{{ $dari }}</strong> s/d <strong>{{ $sampai }}</strong>
                </div>
            @endif
            @if(isset($search) && $search)
                <div>Pencarian: "{{ $search }}"</div>
            @endif
            <div class="small">
                Dicetak pada: {{ now()->format('d-m-Y H:i') }}
            </div>
        </div>
    </div>

    @if(isset($motors) && count($motors))
        <table>
            <thead>
                <tr>
                    <th style="width:30px;">No</th>
                    <th>Nama Motor</th>
                    <th>Brand</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Kenyamanan</th>
                    <th>Perawatan</th>
                    <th>Tanggal Import</th>
                </tr>
            </thead>
            <tbody>
                @foreach($motors as $i => $motor)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $motor->nama_motor }}</td>
                        <td>{{ $motor->brand }}</td>
                        <td>{{ ucfirst(strtolower($motor->kategori)) }}</td>
                        <td class="text-right">
                            Rp {{ number_format($motor->harga, 0, ',', '.') }}
                        </td>
                        <td class="text-center">{{ $motor->kenyamanan }}/5</td>
                        <td class="text-center">{{ $motor->perawatan }}/5</td>
                        <td class="text-center">
                            @if(!empty($motor->tanggal_import))
                                {{ \Carbon\Carbon::parse($motor->tanggal_import)->format('d-m-Y') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center text-muted">Tidak ada data motor untuk dicetak.</p>
    @endif

    <div class="no-print" style="margin-top: 10px; text-align:right;">
        <button onclick="window.print()" class="btn btn-sm btn-primary">
            Cetak Ulang
        </button>
    </div>
</div>

</body>
</html>
