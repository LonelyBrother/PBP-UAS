@extends('layouts.app')

@section('title', 'Data Motor')

@php
    $isAdmin = auth()->check() && (
        (auth()->user()->role ?? auth()->user()->Level) === 'admin'
    );

    // fallback kategori
    $currentKategori = request('kategori', 'all');
@endphp

@section('content')
<div class="container-fluid motor-index-dark">

    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header border-0 py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                {{-- Title --}}
                <div>
                    <h5 class="mb-1 d-flex align-items-center page-title">
                        <span class="icon-circle">
                            <i class="fas fa-motorcycle text-primary"></i>
                        </span>
                        <span>Data Motor</span>
                    </h5>
                    <small class="page-subtitle">
                        Kelola dan analisis data motor berdasarkan kategori dan kriteria.
                    </small>
                </div>

                {{-- Tools --}}
                <div class="motor-toolbar d-flex flex-wrap align-items-center">

                    {{-- Filter kategori --}}
                    <form method="GET" action="{{ route('motor.index') }}">
                        <select name="kategori"
                                class="form-control form-control-sm"
                                onchange="this.form.submit()">
                            <option value="all"     {{ $currentKategori=='all'?'selected':'' }}>Semua kategori</option>
                            <option value="listrik" {{ $currentKategori=='listrik'?'selected':'' }}>Listrik</option>
                            <option value="matic"   {{ $currentKategori=='matic'?'selected':'' }}>Matic</option>
                            <option value="sport"   {{ $currentKategori=='sport'?'selected':'' }}>Sport</option>
                        </select>
                    </form>

                    {{-- Search --}}
                    <form method="GET" action="{{ route('motor.index') }}">
                        <input type="hidden" name="kategori" value="{{ $currentKategori }}">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text"
                                name="search"
                                class="form-control border-0 shadow-none"
                                placeholder="Cari motor / brand..."
                                value="{{ request('search') }}">
                        </div>
                    </form>

                    @if($isAdmin)

                        {{-- EXPORT / IMPORT --}}
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                    type="button"
                                    data-toggle="dropdown">
                                <i class="fas fa-file-alt mr-1"></i>Export / Import
                            </button>

                            <div class="dropdown-menu dropdown-menu-right">
                                {{-- ================= EXPORT ================= --}}
                                <h6 class="dropdown-header">Export</h6>

                                <a class="dropdown-item"
                                href="{{ route('motor.export.excel', [
                                        'kategori' => $currentKategori,
                                        'search'   => request('search')
                                ]) }}">
                                    <i class="fas fa-file-excel text-success mr-2"></i>
                                    Export Excel
                                </a>

                                <a class="dropdown-item"
                                href="{{ route('motor.export.pdf', [
                                        'kategori' => $currentKategori,
                                        'search'   => request('search')
                                ]) }}">
                                    <i class="fas fa-file-pdf text-danger mr-2"></i>
                                    Export PDF
                                </a>

                                <div class="dropdown-divider"></div>

                                {{-- ================= IMPORT ================= --}}
                                <h6 class="dropdown-header">Import</h6>

                                <form action="{{ route('motor.import') }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    class="px-3 py-2">
                                    @csrf

                                    <input type="file"
                                        name="file"
                                        class="form-control form-control-sm mb-2"
                                        accept=".xls,.xlsx"
                                        required>

                                    <button type="submit"
                                            class="btn btn-primary btn-sm btn-block">
                                        <i class="fas fa-upload mr-1"></i>
                                        Import Excel
                                    </button>
                                </form>

                                <div class="dropdown-divider"></div>

                                {{-- ================= CETAK ================= --}}
                                <h6 class="dropdown-header">Cetak</h6>

                                <form action="{{ route('motor.cetak') }}"
                                    method="GET"
                                    target="_blank"
                                    class="px-3 py-2">

                                    <input type="hidden" name="kategori" value="{{ $currentKategori }}">
                                    <input type="hidden" name="search" value="{{ request('search') }}">

                                    <div class="form-group mb-2">
                                        <label class="small mb-1">Dari tanggal</label>
                                        <input type="date"
                                            name="dari"
                                            class="form-control form-control-sm"
                                            required>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="small mb-1">Sampai tanggal</label>
                                        <input type="date"
                                            name="sampai"
                                            class="form-control form-control-sm"
                                            required>
                                    </div>

                                    <button type="submit"
                                            class="btn btn-primary btn-sm btn-block">
                                        <i class="fas fa-print mr-1"></i>
                                        Cetak PDF
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- TAMBAH --}}
                        <a href="{{ route('motor.create') }}?kategori={{ $currentKategori }}"
                        class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i>Tambah
                        </a>

                        {{-- HAPUS SEMUA --}}
                        <button id="btnHapusSemua"
                                class="btn btn-outline-danger btn-sm"
                                {{ $currentKategori !== 'all' ? '' : 'disabled' }}>
                            <i class="fas fa-trash-alt mr-1"></i>Hapus Semua
                        </button>

                    @endif
                </div>
            </div>
        </div>
        {{-- BODY --}}
        <div class="card-body">
            
            {{-- Info --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div class="small text-muted">
                    <span class="badge badge-light border badge-pill">
                        {{ $currentKategori !== 'all'
                            ? 'Kategori: '.ucfirst($currentKategori)
                            : 'Semua kategori' }}
                    </span>
                    @if(request('search'))
                    <span class="badge badge-light border badge-pill">
                        Pencarian: "{{ request('search') }}"
                    </span>
                    @endif
                </div>
                <small class="text-muted">
                    Total data: <strong>{{ $motors->count() }}</strong>
                </small>
            </div>
            
            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Motor</th>
                            <th>Brand</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Kenyamanan</th>
                            <th>Perawatan</th>
                            <th class="text-center" style="width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($motors as $motor)
                        <tr>
                            <td>
                                <strong>{{ $motor->nama_motor }}</strong>
                                <div class="text-muted small">ID: {{ $motor->id }}</div>
                            </td>
                            <td>{{ $motor->brand }}</td>
                            <td>
                                <span class="badge badge-light border">
                                    {{ ucfirst($motor->kategori) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($motor->harga,0,',','.') }}</td>
                            <td>{{ $motor->kenyamanan }}/10</td>
                            <td>{{ $motor->perawatan }}/10</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    {{-- SHOW --}}
                                    <a href="{{ route('motor.show', $motor->id) }}?kategori={{ strtolower($motor->kategori) }}"
                                        class="btn btn-outline-secondary btn-circle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($isAdmin)
                                    {{-- EDIT --}}
                                    <a href="{{ route('motor.edit', $motor->id) }}?kategori={{ strtolower($motor->kategori) }}"
                                        class="btn btn-outline-primary btn-circle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- DELETE --}}
                                    <form action="{{ route('motor.destroy', $motor->id) }}?kategori={{ strtolower($motor->kategori) }}"
                                        method="POST"
                                        class="d-inline form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-circle">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-2"></i><br>
                                Belum ada data motor.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
{{-- PAGINATION --}}
@if(method_exists($motors, 'links'))
    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap">

        {{-- info halaman --}}
        <div class="small text-muted">
            Menampilkan
            {{ $motors->firstItem() ?? 0 }}
            –
            {{ $motors->lastItem() ?? 0 }}
            dari
            {{ $motors->total() }}
            data
        </div>

        {{-- tombol halaman --}}
        <div>
            {{ $motors->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif

@if($isAdmin)
<form id="formHapusSemua"
action="{{ route('motor.destroyAll') }}?kategori={{ $currentKategori }}"
method="POST" class="d-none">
@csrf
@method('DELETE')
</form>
@endif

@push('styles')
<style>
/* ===================================================
   MOTOR INDEX – DARK THEME (SCOPED & SAFE)
   =================================================== */

.motor-index-dark .card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
}

.motor-index-dark .card-header {
    background: linear-gradient(180deg, #020617, #0b1220);
}

/* Title */
.motor-index-dark .page-title span:last-child {
    color: #f8fafc;
}

.motor-index-dark .page-subtitle {
    color: #94a3b8;
}

/* Icon */
.motor-index-dark .icon-circle {
    background: rgba(59,130,246,.15);
    color: #60a5fa;
}

/* Filter & Search */
.motor-index-dark .form-control,
.motor-index-dark .input-group-text {
    background: #020617;
    border: 1px solid rgba(255,255,255,.1);
    color: #e5e7eb;
}

.motor-index-dark .form-control::placeholder {
    color: #64748b;
}

/* Table */
.motor-index-dark .table {
    color: #e5e7eb;
}

.motor-index-dark .table thead {
    background: #020617;
}

.motor-index-dark .table thead th {
    color: #94a3b8;
    font-size: .7rem;
    letter-spacing: .08em;
    border-bottom: 1px solid rgba(255,255,255,.08);
}

.motor-index-dark .table tbody tr:hover {
    background: rgba(255,255,255,.04);
}

.motor-index-dark .table td {
    border-top: 1px solid rgba(255,255,255,.06);
}

/* Badge */
.motor-index-dark .badge {
    background: rgba(255,255,255,.08);
    color: #e5e7eb;
}

/* Action buttons */
.motor-index-dark .btn-outline-secondary,
.motor-index-dark .btn-outline-primary,
.motor-index-dark .btn-outline-danger {
    border-color: rgba(255,255,255,.15);
    color: #e5e7eb;
}

.motor-index-dark .btn-outline-secondary:hover {
    background: rgba(255,255,255,.08);
}

.motor-index-dark .btn-outline-primary:hover {
    background: rgba(59,130,246,.25);
}

.motor-index-dark .btn-outline-danger:hover {
    background: rgba(239,68,68,.25);
}

/* Pagination */
.motor-index-dark .pagination .page-link {
    background: #020617;
    border: 1px solid rgba(255,255,255,.1);
    color: #cbd5f5;
}

.motor-index-dark .pagination .page-item.active .page-link {
    background: #2563eb;
    border-color: #2563eb;
}

/* Text muted */
.motor-index-dark .text-muted {
    color: #94a3b8 !important;
}
</style>
@endpush

@endsection
