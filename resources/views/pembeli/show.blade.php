@extends('layouts.app')

@section('title', 'Detail Pembeli')

@php
    $isAdmin = auth()->check() && (
        (auth()->user()->role ?? null) === 'admin' ||
        (auth()->user()->Level ?? null) === 'admin'
    );
@endphp

@section('content')
<div class="container-fluid py-4 pembeli-show-dark">
    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header border-0 pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">

                <div>
                    <h5 class="mb-1 d-flex align-items-center pembeli-show-title">
                        <span class="rounded-circle d-inline-flex justify-content-center align-items-center pembeli-show-icon">
                            <i class="fas fa-user text-primary"></i>
                        </span>
                        <span>Detail Pembeli</span>
                    </h5>
                    <small class="text-muted pembeli-show-subtitle">
                        Informasi lengkap pembeli yang terdaftar.
                    </small>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('pembeli.index') }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>

                    @if($isAdmin)
                        <a href="{{ route('pembeli.edit', $pembeli->id) }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                    @endif
                </div>

            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body pt-3">

            <div class="row">
                <div class="col-md-12">

                    {{-- INFO LIST --}}
                    <div class="pembeli-info-box">
                        <div class="info-row">
                            <span class="info-label">Nama Pembeli</span>
                            <span class="info-value">{{ $pembeli->nama }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Alamat Lengkap</span>
                            <span class="info-value">{{ $pembeli->alamat }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">No. Telepon</span>
                            <span class="info-value">{{ $pembeli->telepon }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Provinsi</span>
                            <span class="info-value">{{ $pembeli->provinsi_nama ?? '-' }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Kabupaten / Kota</span>
                            <span class="info-value">{{ $pembeli->kabupaten_nama ?? '-' }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Kecamatan</span>
                            <span class="info-value">{{ $pembeli->kecamatan_nama ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- READ ONLY INFO --}}
                    @if(!$isAdmin)
                        <div class="alert alert-secondary mt-4 small">
                            <i class="fas fa-lock mr-1"></i>
                            Mode baca saja. Data pembeli hanya dapat diubah oleh admin.
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
   PEMBELI SHOW – DARK MODERN THEME
   =================================================== */

.pembeli-show-dark .card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
}

.pembeli-show-dark .card-header {
    background: linear-gradient(180deg, #020617, #0b1220);
}

/* Title */
.pembeli-show-title span:last-child {
    color: #f8fafc;
}

.pembeli-show-subtitle {
    color: #94a3b8;
}

/* Icon */
.pembeli-show-icon {
    background: rgba(59,130,246,.15);
    color: #60a5fa;
}

/* Info box */
.pembeli-info-box {
    background: #020617;
    border: 1px solid rgba(255,255,255,.08);
    border-radius: .75rem;
    padding: 1.25rem;
}

/* Info row */
.info-row {
    display: flex;
    flex-wrap: wrap;
    padding: .65rem 0;
    border-bottom: 1px dashed rgba(255,255,255,.08);
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    width: 220px;
    color: #94a3b8;
    font-size: .85rem;
}

.info-value {
    color: #e5e7eb;
    font-weight: 500;
}

/* Alert readonly */
.pembeli-show-dark .alert-secondary {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    color: #cbd5f5;
}
</style>
@endpush
