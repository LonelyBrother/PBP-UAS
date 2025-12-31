@extends('layouts.app')

@section('title', 'Detail Motor')

@php
    $isAdmin = auth()->check() && (
        (auth()->user()->role ?? null) === 'admin' ||
        (auth()->user()->Level ?? null) === 'admin'
    );

    // kategori WAJIB dari query string
    $kategori = request('kategori');
@endphp

@section('content')
<div class="container-fluid py-4 motor-show-dark">
    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header border-0 pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>
                    <h5 class="mb-1 d-flex align-items-center page-title">
                        <span class="icon-circle">
                            <i class="fas fa-motorcycle text-primary"></i>
                        </span>
                        <span>Detail Motor</span>
                    </h5>
                    <small class="page-subtitle">
                        Informasi lengkap mengenai motor ini.
                    </small>
                </div>

                <div class="btn-group">

                    {{-- KEMBALI --}}
                    <a href="{{ route('motor.index') }}?kategori={{ $kategori }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>

                    {{-- EDIT --}}
                    @if($isAdmin)
                        <a href="{{ route('motor.edit', $motor->id) }}?kategori={{ $kategori }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body mt-3">
            <div class="row g-4">

                {{-- GAMBAR --}}
                <div class="col-lg-4 col-md-5 text-center">
                    <div class="motor-image-wrapper">
                        @if($motor->gambar)
                            <img src="{{ asset('storage/'.$motor->gambar) }}"
                                 class="motor-image"
                                 alt="{{ $motor->nama_motor }}">
                        @else
                            <div class="d-flex justify-content-center align-items-center h-100 text-muted">
                                Tidak ada gambar
                            </div>
                        @endif
                    </div>
                </div>

                {{-- INFO --}}
                <div class="col-lg-8 col-md-7">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th>Nama Motor</th>
                            <td>{{ $motor->nama_motor }}</td>
                        </tr>
                        <tr>
                            <th>Brand</th>
                            <td>{{ $motor->brand }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                <span class="badge">
                                    {{ ucfirst($kategori) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td>Rp {{ number_format($motor->harga,0,',','.') }}</td>
                        </tr>
                        <tr>
                            <th>Kenyamanan</th>
                            <td>{{ $motor->kenyamanan }} / 10</td>
                        </tr>
                        <tr>
                            <th>Perawatan</th>
                            <td>{{ $motor->perawatan }} / 10</td>
                        </tr>
                        <tr>
                            <th>Tanggal Import</th>
                            <td>{{ $motor->tanggal_import ? dateID($motor->tanggal_import) : '-' }}</td>
                        </tr>
                    </table>

                    @if(!$isAdmin)
                        <div class="alert alert-dark small mt-3">
                            <i class="fas fa-lock mr-1"></i>
                            Mode baca saja.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ===================================================
   MOTOR SHOW – DARK THEME (SAFE & SCOPED)
   =================================================== */

.motor-show-dark .card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
}

.motor-show-dark .card-header {
    background: linear-gradient(180deg, #020617, #0b1220);
}

/* Title */
.motor-show-dark .page-title span:last-child {
    color: #f8fafc;
}

.motor-show-dark .page-subtitle {
    color: #94a3b8;
}

/* Icon */
.motor-show-dark .icon-circle {
    background: rgba(59,130,246,.15);
    color: #60a5fa;
}

/* Image wrapper */
.motor-show-dark .motor-image-wrapper {
    background: #020617;
    border: 1px solid rgba(255,255,255,.08);
}

/* Table */
.motor-show-dark table {
    color: #e5e7eb;
}

.motor-show-dark table th {
    width: 160px;
    color: #94a3b8;
    font-size: .8rem;
    font-weight: 600;
}

.motor-show-dark table td {
    font-size: .9rem;
    color: #e5e7eb;
}

/* Badge */
.motor-show-dark .badge {
    background: rgba(255,255,255,.08);
    color: #e5e7eb;
}

/* Buttons */
.motor-show-dark .btn-outline-secondary {
    border-color: rgba(255,255,255,.15);
    color: #e5e7eb;
}

.motor-show-dark .btn-outline-secondary:hover {
    background: rgba(255,255,255,.08);
}

.motor-show-dark .btn-primary {
    background: #2563eb;
    border-color: #2563eb;
}

/* Alert */
.motor-show-dark .alert-dark {
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.08);
    color: #cbd5f5;
}
</style>
@endpush
