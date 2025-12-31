<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Ranking TOPSIS</title>

    <style>
        @page {
            size: A4;
            margin: 1.6cm;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10.5px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #1f65b8;
        }

        .kategori-title {
            margin-top: 18px;
            margin-bottom: 6px;
            padding: 6px 10px;
            background: #eef4ff;
            border-left: 4px solid #1f65b8;
            font-size: 12px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        thead th {
            background: #1f65b8;
            color: #fff;
            font-size: 10px;
            padding: 6px;
            border: 1px solid #cbd5e1;
            text-align: center;
        }

        tbody td {
            border: 1px solid #cbd5e1;
            padding: 6px;
            font-size: 10px;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        /* ===== TOP 3 HIGHLIGHT ===== */
        .rank-1 {
            background: #fff7d6;
            font-weight: bold;
        }

        .rank-2 {
            background: #eef2f7;
        }

        .rank-3 {
            background: #fff0e6;
        }

        .nilai {
            text-align: center;
            font-weight: 600;
        }

        footer {
            position: fixed;
            bottom: 1cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #777;
        }
    </style>
</head>
<body>

<h2>Laporan Hasil Ranking Metode TOPSIS</h2>

@php
    $rankingPerKategori = $rankingPerKategori ?? collect();
@endphp

@foreach($rankingPerKategori as $kategori => $items)

    <div class="kategori-title">
        Kategori: {{ ucfirst($kategori) }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:6%">#</th>
                <th style="width:34%">Nama Motor</th>
                <th style="width:25%">Brand</th>
                <th style="width:20%">Nilai Preferensi</th>
                <th style="width:15%">Peringkat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items->values() as $i => $row)
                @php
                    $rankClass = $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : ''));
                @endphp
                <tr class="{{ $rankClass }}">
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $row['nama_motor'] }}</td>
                    <td>{{ $row['brand'] }}</td>
                    <td class="nilai">{{ number_format($row['nilai'], 4) }}</td>
                    <td class="text-center">{{ $i + 1 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endforeach

<footer>
    Dicetak pada {{ dateID(now()) }} — Sistem Pendukung Keputusan Pemilihan Motor (TOPSIS)
</footer>

</body>
</html>
