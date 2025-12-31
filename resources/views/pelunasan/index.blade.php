@extends('layouts.app')

@section('title', 'Data Pelunasan Motor')

@php
    $isAdmin = auth()->check() && (
        (auth()->user()->role ?? null) === 'admin' ||
        (auth()->user()->Level ?? null) === 'admin'
    );
@endphp

@section('content')
<div class="container-fluid py-4 pelunasan-index-dark">
    <div class="card border-0 shadow-sm">

        {{-- ================= HEADER ================= --}}
        <div class="card-header border-0 pb-0">
            <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap:.5rem;">

                {{-- Title --}}
                <div>
                    <h5 class="mb-1 d-flex align-items-center pelunasan-title">
                        <span class="rounded-circle d-inline-flex justify-content-center align-items-center pelunasan-icon">
                            <i class="fas fa-file-invoice-dollar text-primary"></i>
                        </span>
                        <span>Data Pelunasan Motor</span>
                    </h5>
                    <small class="text-muted pelunasan-subtitle">
                        Catatan pelunasan motor berdasarkan pembeli dan status.
                    </small>
                </div>

                {{-- Tools kanan --}}
                <div class="d-flex flex-wrap align-items-center justify-content-end gap-2">

                    {{-- Search --}}
                    <form method="GET" action="{{ route('pelunasan.index') }}">
                        <div class="input-group input-group-sm pelunasan-search">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Cari no pelunasan / pembeli / status..."
                                   value="{{ request('search') }}">
                        </div>
                    </form>

                    {{-- ADMIN ONLY --}}
                    @if($isAdmin)
                        <a href="{{ route('pelunasan.create') }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i>Tambah
                        </a>

                        <button id="btnHapusSemua"
                                class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash-alt mr-1"></i>Hapus Semua
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- ================= BODY ================= --}}
        <div class="card-body pt-3">

            {{-- Flash Message --}}
            @if (session('success'))
                <div class="alert alert-success small border-0 shadow-sm mb-3">
                    <i class="fas fa-check-circle mr-1"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Info --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                <div class="small text-muted">
                    @if(request('search'))
                        <span class="badge pelunasan-badge">
                            <i class="fas fa-search mr-1"></i>
                            Pencarian: "{{ request('search') }}"
                        </span>
                    @else
                        <span class="badge pelunasan-badge">
                            <i class="fas fa-list mr-1"></i>
                            Semua data pelunasan
                        </span>
                    @endif
                </div>

                @if($pelunasan->count())
                    <small class="text-muted">
                        Total data: <strong>{{ $pelunasan->count() }}</strong>
                    </small>
                @endif
            </div>

            {{-- ================= TABLE ================= --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 pelunasan-table">
                    <thead>
                        <tr>
                            <th style="width:140px;">No Pelunasan</th>
                            <th>Nama Pembeli</th>
                            <th>Daftar Motor</th>
                            <th class="text-center" style="width:120px;">Status</th>
                            <th style="width:150px;">Tanggal</th>
                            <th class="text-center" style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelunasan as $p)
                            @php
                                $motors = is_array($p->daftar_motor)
                                    ? $p->daftar_motor
                                    : (json_decode($p->daftar_motor, true) ?? []);
                            @endphp
                            <tr>
                                <td><strong>{{ $p->no_pelunasan }}</strong></td>
                                <td>{{ $p->pembeli->nama ?? '-' }}</td>
                                <td>
                                    @if(count($motors))
                                        <ul class="mb-0 pl-3">
                                            @foreach($motors as $m)
                                                <li>{{ $m }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $p->status === 'Lunas' ? 'badge-success' : 'badge-warning' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td>
                                    {{ $p->tanggal_pelunasan
                                        ? \Carbon\Carbon::parse($p->tanggal_pelunasan)->translatedFormat('d F Y')
                                        : '-' }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('pelunasan.show', $p->id) }}"
                                           class="btn btn-outline-secondary btn-circle"
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($isAdmin)
                                            <a href="{{ route('pelunasan.edit', $p->id) }}"
                                               class="btn btn-outline-primary btn-circle"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('pelunasan.destroy', $p->id) }}"
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
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    Belum ada data pelunasan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($pelunasan, 'links') && $pelunasan->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $pelunasan->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>
</div>

{{-- FORM HAPUS SEMUA --}}
@if($isAdmin)
<form id="formHapusSemua"
      action="{{ route('pelunasan.destroyAll') }}"
      method="POST"
      class="d-none">
    @csrf
    @method('DELETE')
</form>
@endif
@endsection

@push('styles')
<style>
/* ===================================================
   PELUNASAN INDEX – DARK MODERN THEME
   =================================================== */

.pelunasan-index-dark .card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
}

.pelunasan-index-dark .card-header {
    background: linear-gradient(180deg, #020617, #0b1220);
}

/* Title */
.pelunasan-title span:last-child {
    color: #f8fafc;
}

.pelunasan-subtitle {
    color: #94a3b8;
}

/* Icon */
.pelunasan-icon {
    background: rgba(59,130,246,.15);
    color: #60a5fa;
}

/* Search */
.pelunasan-search .input-group-text {
    background: #020617;
    border: 1px solid rgba(255,255,255,.12);
    color: #94a3b8;
}

.pelunasan-search .form-control {
    background: #020617;
    border: 1px solid rgba(255,255,255,.12);
    color: #e5e7eb;
}

/* Badge info */
.pelunasan-badge {
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    color: #e5e7eb;
}

/* Table */
.pelunasan-table thead {
    background: #020617;
}

.pelunasan-table th {
    color: #9ca3af;
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .06em;
}

.pelunasan-table td {
    color: #e5e7eb;
}

/* Status badge */
.badge-success {
    background: rgba(16,185,129,.2);
    color: #6ee7b7;
}

.badge-warning {
    background: rgba(251,191,36,.2);
    color: #fde68a;
}
</style>
@endpush
