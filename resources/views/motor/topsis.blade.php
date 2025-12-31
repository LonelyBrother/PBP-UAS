@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Hasil Perhitungan TOPSIS</h2>

    <div class="mb-3">
        <a href="{{ route('motor.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Ranking</th>
                <th>Nama Motor</th>
                <th>Brand</th>
                <th>Kategori</th>
                <th>Nilai Preferensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ranking as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r['nama_motor'] }}</td>
                <td>{{ $r['brand'] }}</td>
                <td>{{ ucfirst($r['kategori']) }}</td>
                <td><strong>{{ $r['nilai'] }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
