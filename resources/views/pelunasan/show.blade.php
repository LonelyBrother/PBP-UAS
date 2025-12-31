@extends('layouts.app')

@section('title', 'Detail Pelunasan')

@php
    $isAdmin = auth()->check() && (
        (auth()->user()->role ?? null) === 'admin' ||
        (auth()->user()->Level ?? null) === 'admin'
    );

    $motors = is_array($pelunasan->daftar_motor)
        ? $pelunasan->daftar_motor
        : (json_decode($pelunasan->daftar_motor, true) ?? []);
@endphp

@section('content')
<div class="container-fluid py-4 pelunasan-show-style">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap mb-4" style="gap:1rem;">
        <div>
            <h4 class="mb-1 text-white fw-semibold d-flex align-items-center">
                <i class="fas fa-file-invoice-dollar mr-2 text-primary"></i>
                Detail Pelunasan
            </h4>
            <small class="text-muted">
                Informasi lengkap data pelunasan motor.
            </small>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('pelunasan.index') }}"
               class="btn btn-outline-light btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>

            @if($isAdmin)
                <a href="{{ route('pelunasan.edit', $pelunasan->id) }}"
                   class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
            @endif
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="row g-4">

        {{-- INFO --}}
        <div class="col-md-6">
            <div class="pelunasan-box">
                <div class="pelunasan-item">
                    <span class="label">No Pelunasan</span>
                    <span class="value pelunasan-no">
                        {{ $pelunasan->no_pelunasan }}
                    </span>
                </div>

                <div class="pelunasan-item">
                    <span class="label">Nama Pembeli</span>
                    <span class="value">
                        {{ $pelunasan->pembeli->nama ?? '-' }}
                    </span>
                </div>

                <div class="pelunasan-item">
                    <span class="label">Status</span>
                    <span class="value">
                        <span class="badge {{ $pelunasan->status === 'Lunas' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $pelunasan->status }}
                        </span>
                    </span>
                </div>

                <div class="pelunasan-item">
                    <span class="label">Tanggal Pelunasan</span>
                    <span class="value">
                        {{ $pelunasan->tanggal_pelunasan
                            ? \Carbon\Carbon::parse($pelunasan->tanggal_pelunasan)->translatedFormat('d F Y')
                            : '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- MOTOR --}}
        <div class="col-md-6">
            <div class="pelunasan-box">
                <div class="mb-2 text-muted fw-semibold">
                    Daftar Motor
                </div>

                @if(count($motors))
                    <ul class="motor-list">
                        @foreach($motors as $m)
                            <li>
                                <i class="fas fa-motorcycle mr-2 text-muted"></i>
                                {{ $m }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <span class="text-muted">Tidak ada data motor</span>
                @endif
            </div>
        </div>

    </div>

    {{-- ADMIN DELETE --}}
    @if($isAdmin)
        <div class="mt-4 d-flex justify-content-end">
            <form action="{{ route('pelunasan.destroy', $pelunasan->id) }}"
                  method="POST"
                  class="form-hapus">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-trash mr-1"></i>Hapus Data
                </button>
            </form>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* ===================================================
   PELUNASAN SHOW – DARK MOTOR STYLE
   =================================================== */

.pelunasan-show-style {
    color: #e5e7eb;
}

/* BOX */
.pelunasan-box {
    background: #020617;
    border: 1px solid rgba(255,255,255,.08);
    border-radius: .75rem;
    padding: 1.25rem;
}

/* ITEM */
.pelunasan-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .6rem 0;
    border-bottom: 1px dashed rgba(255,255,255,.08);
}

.pelunasan-item:last-child {
    border-bottom: none;
}

.pelunasan-item .label {
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #94a3b8;
}

.pelunasan-item .value {
    font-size: .9rem;
    font-weight: 500;
}

/* NO PELUNASAN */
.pelunasan-no {
    font-size: .8rem;
    letter-spacing: .04em;
}

/* MOTOR LIST */
.motor-list {
    list-style: none;
    padding-left: 0;
    margin: 0;
}

.motor-list li {
    padding: .4rem 0;
    border-bottom: 1px solid rgba(255,255,255,.06);
}

.motor-list li:last-child {
    border-bottom: none;
}

/* BADGE */
.badge.bg-success {
    background: rgba(16,185,129,.25) !important;
    color: #6ee7b7;
}

.badge.bg-warning {
    background: rgba(251,191,36,.25) !important;
}
</style>
@endpush
