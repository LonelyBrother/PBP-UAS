@extends('layouts.app')

@section('title', 'Data Pembeli')

@php
    $isAdmin = auth()->check() && (
        (auth()->user()->role ?? null) === 'admin' ||
        (auth()->user()->Level ?? null) === 'admin'
    );
@endphp

@section('content')
<div class="container-fluid pembeli-index-dark py-4">

    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header border-0 py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">

                <div>
                    <h5 class="mb-1 d-flex align-items-center page-title">
                        <span class="icon-circle">
                            <i class="fas fa-users text-primary"></i>
                        </span>
                        <span>Data Pembeli</span>
                    </h5>
                    <small class="page-subtitle">
                        Kelola informasi pembeli dan alamat mereka.
                    </small>
                </div>

                <div class="d-flex align-items-center" style="gap:.5rem;">
                    {{-- SEARCH --}}
                    <form method="GET" action="{{ route('pembeli.index') }}">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   class="form-control border-0 shadow-none"
                                   placeholder="Cari nama / alamat..."
                                   value="{{ request('search') }}">
                        </div>
                    </form>

                    {{-- ADMIN --}}
                    @if($isAdmin)
                        <a href="{{ route('pembeli.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i>Tambah
                        </a>

                        <button id="btnHapusSemua" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash-alt mr-1"></i>Hapus Semua
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            {{-- FLASH --}}
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex justify-content-between align-items-center mb-3">
                    <span><i class="fas fa-check-circle mr-1"></i>{{ session('success') }}</span>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            {{-- INFO --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                <div class="small text-muted">
                    @if(request('search'))
                        <span class="badge badge-light border badge-pill">
                            <i class="fas fa-search mr-1"></i>
                            Pencarian: "{{ request('search') }}"
                        </span>
                    @else
                        <span class="badge badge-light border badge-pill">
                            <i class="fas fa-list mr-1"></i>
                            Semua pembeli
                        </span>
                    @endif
                </div>

                @if($pembeli->count())
                    <small class="text-muted">
                        Total data: <strong>{{ $pembeli->count() }}</strong>
                    </small>
                @endif
            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Provinsi</th>
                            <th>Kabupaten</th>
                            <th>Kecamatan</th>
                            <th class="text-center" style="width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembeli as $p)
                            <tr>
                                <td><strong>{{ $p->nama }}</strong></td>
                                <td>{{ $p->alamat }}</td>
                                <td>{{ $p->telepon }}</td>
                                <td>{{ $p->provinsi_nama ?? '-' }}</td>
                                <td>{{ $p->kabupaten_nama ?? '-' }}</td>
                                <td>{{ $p->kecamatan_nama ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">

                                        {{-- SHOW --}}
                                        <a href="{{ route('pembeli.show', $p->id) }}"
                                           class="btn btn-outline-secondary btn-circle"
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($isAdmin)
                                            <a href="{{ route('pembeli.edit', $p->id) }}"
                                               class="btn btn-outline-primary btn-circle"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('pembeli.destroy', $p->id) }}"
                                                  method="POST"
                                                  class="d-inline form-hapus">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-outline-danger btn-circle"
                                                        title="Hapus">
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
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    Belum ada data pembeli.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($pembeli, 'links') && $pembeli->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $pembeli->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>
</div>

{{-- FORM HAPUS SEMUA --}}
@if($isAdmin)
<form id="formHapusSemua"
      action="{{ route('pembeli.destroyAll') }}"
      method="POST"
      class="d-none">
    @csrf
    @method('DELETE')
</form>
@endif
@endsection

@push('styles')
<style>
/* =====================================================
   PEMBELI INDEX – DARK MODERN (ADMIN LTE SAFE OVERRIDE)
   ===================================================== */

.pembeli-index-dark {
    color: #e5e7eb;
}

/* Card */
.pembeli-index-dark .card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
    border-radius: 1rem;
}

/* Header */
.pembeli-index-dark .card-header {
    background: linear-gradient(180deg, #020617, #0b1220);
}

/* Title */
.pembeli-index-dark .page-title span:last-child {
    color: #f8fafc;
}

.pembeli-index-dark .page-subtitle {
    color: #94a3b8;
}

/* Icon */
.pembeli-index-dark .icon-circle {
    background: rgba(59,130,246,.15);
    color: #60a5fa;
}

/* SEARCH INPUT */
.pembeli-index-dark .input-group-text {
    background: #020617 !important;
    color: #94a3b8;
    border: 1px solid rgba(255,255,255,.12);
}

.pembeli-index-dark input.form-control {
    background: #020617 !important;
    color: #e5e7eb !important;
    border: 1px solid rgba(255,255,255,.12);
}

.pembeli-index-dark input::placeholder {
    color: #64748b;
}

/* Table */
.pembeli-index-dark .table {
    color: #e5e7eb;
}

.pembeli-index-dark .table thead {
    background: #020617;
}

.pembeli-index-dark .table thead th {
    color: #94a3b8;
    font-size: .7rem;
    text-transform: uppercase;
    letter-spacing: .08em;
}

.pembeli-index-dark .table td {
    border-top: 1px solid rgba(255,255,255,.05);
}

.pembeli-index-dark .table tbody tr:hover {
    background: rgba(255,255,255,.04);
}

/* Badge */
.pembeli-index-dark .badge-light {
    background: rgba(255,255,255,.08);
    color: #e5e7eb;
}

/* Alert SUCCESS */
.pembeli-index-dark .alert-success {
    background: rgba(34,197,94,.15);
    color: #bbf7d0;
}

/* Buttons */
.pembeli-index-dark .btn-primary {
    background: #2563eb;
    border-color: #2563eb;
}

.pembeli-index-dark .btn-outline-secondary,
.pembeli-index-dark .btn-outline-primary,
.pembeli-index-dark .btn-outline-danger {
    border-color: rgba(255,255,255,.15);
    color: #e5e7eb;
}

.pembeli-index-dark .btn-outline-secondary:hover {
    background: rgba(255,255,255,.08);
}

.pembeli-index-dark .btn-outline-primary:hover {
    background: rgba(59,130,246,.2);
}

.pembeli-index-dark .btn-outline-danger:hover {
    background: rgba(239,68,68,.2);
}

/* Pagination */
.pembeli-index-dark .pagination .page-link {
    background: #020617;
    border: 1px solid rgba(255,255,255,.12);
    color: #cbd5f5;
}

.pembeli-index-dark .pagination .page-item.active .page-link {
    background: #2563eb;
    border-color: #2563eb;
}

/* Muted text */
.pembeli-index-dark .text-muted {
    color: #94a3b8 !important;
}
</style>
@endpush
