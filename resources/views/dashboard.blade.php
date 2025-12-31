@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4 dashboard-dark">

    {{-- ================= STAT CARDS ================= --}}
    <div class="row g-3">

        {{-- JUMLAH MOTOR --}}
        <div class="col-lg-4 col-md-6">
            <div class="dashboard-card stat-card">
                <div class="stat-content">
                    <div>
                        <h3 class="stat-value">{{ $motorCount }}</h3>
                        <p class="stat-label">Total Motor</p>
                    </div>
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-motorcycle"></i>
                    </div>
                </div>
                <a href="{{ route('motor.index') }}" class="stat-link">
                    Lihat Data <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        {{-- KATEGORI MOTOR --}}
        <div class="col-lg-4 col-md-6">
            <div class="dashboard-card">
                <div class="card-header-clean">
                    <h6 class="card-title">
                        <i class="fas fa-layer-group mr-1"></i>Kategori Motor
                    </h6>
                </div>

                <div class="card-body-clean">
                    <div class="category-item">
                        <span>Matic</span>
                        <strong>{{ $maticCount }}</strong>
                    </div>
                    <div class="category-item">
                        <span>Sport</span>
                        <strong>{{ $sportCount }}</strong>
                    </div>
                    <div class="category-item">
                        <span>Listrik</span>
                        <strong>{{ $listrikCount }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- AKSI --}}
        <div class="col-lg-4 col-md-12">
            <div class="dashboard-card">
                <div class="card-header-clean">
                    <h6 class="card-title">
                        <i class="fas fa-bolt mr-1"></i>Aksi Cepat
                    </h6>
                </div>

                <div class="card-body-clean">
                    <a href="{{ route('motor.index') }}" class="action-link">
                        <i class="fas fa-database"></i>
                        Kelola Data Motor
                    </a>
                    <a href="{{ route('motor.topsis') }}" class="action-link">
                        <i class="fas fa-chart-line"></i>
                        Perhitungan TOPSIS
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= DESKRIPSI ================= --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="dashboard-card">
                <div class="card-header-clean">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>Deskripsi Sistem
                    </h6>
                </div>
                <div class="card-body-clean text-muted">
                    <p class="mb-0">
                        Sistem Pendukung Keputusan ini membantu pengguna memilih motor
                        terbaik berdasarkan kriteria <strong>harga</strong>,
                        <strong>kenyamanan</strong>, dan <strong>perawatan</strong>
                        menggunakan metode <strong>TOPSIS</strong> secara objektif dan terukur.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
/* ===================================================
   DASHBOARD – DARK MODERN UI
   =================================================== */

.dashboard-dark {
    color: #e5e7eb;
}

/* CARD */
.dashboard-card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
    border-radius: 1rem;
    height: 100%;
    box-shadow: 0 10px 25px rgba(0,0,0,.35);
}

/* HEADER */
.card-header-clean {
    padding: .9rem 1.1rem .4rem;
}

.card-title {
    font-size: .85rem;
    color: #cbd5f5;
    font-weight: 600;
}

/* BODY */
.card-body-clean {
    padding: 1rem 1.1rem 1.2rem;
}

/* STAT CARD */
.stat-card {
    padding: 1.2rem;
}

.stat-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: .2rem;
}

.stat-label {
    font-size: .8rem;
    color: #94a3b8;
}

.stat-icon {
    width: 46px;
    height: 46px;
    border-radius: .75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
}

.stat-link {
    display: inline-block;
    margin-top: .8rem;
    font-size: .75rem;
    color: #60a5fa;
    text-decoration: none;
}

.stat-link:hover {
    text-decoration: underline;
}

/* CATEGORY */
.category-item {
    display: flex;
    justify-content: space-between;
    padding: .45rem 0;
    border-bottom: 1px dashed rgba(255,255,255,.08);
    font-size: .85rem;
}

.category-item:last-child {
    border-bottom: none;
}

/* ACTION */
.action-link {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .6rem .75rem;
    margin-bottom: .5rem;
    border-radius: .5rem;
    background: rgba(255,255,255,.04);
    color: #e5e7eb;
    font-size: .85rem;
    text-decoration: none;
}

.action-link i {
    color: #60a5fa;
}

.action-link:hover {
    background: rgba(59,130,246,.15);
}

/* ICON COLOR */
.bg-primary {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
}
</style>
@endpush
