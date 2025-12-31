<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Motor</title>

    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            color: #333;
            margin: 0;
            background-color: #fff;
            font-size: 12px;
        }

        header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 5px;
        }

        header h1 {
            color: #0056b3;
            font-size: 18px;
            margin: 0;
        }

        header p {
            color: #555;
            font-size: 11px;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #d0d7e2;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #d0d7e2;
            padding: 6px 8px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #0056b3;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
        }

        td {
            font-size: 11px;
        }

        tr:nth-child(even) {
            background-color: #f5f8ff;
        }

        tr:hover {
            background-color: #eef5ff;
        }

        td.harga {
            text-align: right;
            font-weight: 500;
            color: #1a237e;
        }

        img {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid #ccc;
        }

        .no-img {
            color: #999;
            font-style: italic;
            font-size: 10px;
        }

        footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 25px;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }

        .summary {
            margin-top: 8px;
            text-align: right;
            font-size: 11px;
            color: #333;
        }

        /* Supaya tabel tidak keluar area print */
        main {
            max-width: 100%;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <header>
        <h1>Laporan Data Motor</h1>
        <p>Tanggal Cetak: {{ dateID(now()) }}</p>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Gambar</th>
                    <th style="width: 18%;">Nama Motor</th>
                    <th style="width: 12%;">Brand</th>
                    <th style="width: 10%;">Kategori</th>
                    <th style="width: 12%;">Harga</th>
                    <th style="width: 10%;">Kenyamanan</th>
                    <th style="width: 10%;">Perawatan</th>
                    <th style="width: 15%;">Tanggal Import</th>
                </tr>
            </thead>
            <tbody>
                @forelse($motors as $motor)
                <tr>
                    <td>
                        @if($motor->gambar)
                            <img src="{{ public_path('storage/' . $motor->gambar) }}" alt="{{ $motor->nama_motor }}">
                        @else
                            <span class="no-img">Tidak ada</span>
                        @endif
                    </td>
                    <td>{{ $motor->nama_motor }}</td>
                    <td>{{ $motor->brand }}</td>
                    <td>{{ ucfirst($motor->kategori) }}</td>
                    <td class="harga">Rp{{ number_format($motor->harga, 0, ',', '.') }}</td>
                    <td>{{ $motor->kenyamanan }}</td>
                    <td>{{ $motor->perawatan }}</td>
                    <td>{{ dateID($motor->tanggal_import) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:#888;">Data motor tidak tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary">
            <p><strong>Total Data:</strong> {{ $motors->count() }}</p>
        </div>
    </main>

    <footer>
        <p>Dicetak otomatis pada {{ dateID(now()) }} oleh Sistem Manajemen Motor</p>
    </footer>
</body>
</html>
