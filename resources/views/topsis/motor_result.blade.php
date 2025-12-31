@extends('layouts.app')

@section('title', 'Hasil Ranking TOPSIS')

@section('content')
<div class="container-fluid topsis-dark py-4">
    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header border-0 pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-1 d-flex align-items-center topsis-title">
                        <span class="rounded-circle d-inline-flex justify-content-center align-items-center topsis-icon">
                            <i class="fas fa-trophy"></i>
                        </span>
                        <span>Hasil Ranking TOPSIS</span>
                    </h5>
                    <small class="text-muted">
                        Ranking motor dihitung terpisah per kategori agar lebih adil.
                    </small>
                </div>

                <div class="btn-group">
                    <a href="{{ route('topsis.formBobot') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-sliders-h mr-1"></i>Ubah Bobot
                    </a>
                    <a href="{{ route('topsis.exportPdf') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf mr-1"></i>Export PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">

            {{-- TABS --}}
            <ul class="nav nav-pills mb-3" role="tablist">
                @foreach($hasil as $kategori => $data)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                           data-toggle="pill"
                           href="#tab-{{ $kategori }}">
                            {{ ucfirst($kategori) }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- TAB CONTENT --}}
            <div class="tab-content">
                @foreach($hasil as $kategori => $ranking)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                         id="tab-{{ $kategori }}">

                        @if($ranking->isEmpty())
                            <div class="alert alert-warning text-center">
                                Tidak ada data motor kategori {{ ucfirst($kategori) }}.
                            </div>
                        @else

                            {{-- 🔥 SCROLLABLE TABLE --}}
                            <div class="topsis-table-wrapper">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:60px;">Rank</th>
                                            <th>Nama Motor</th>
                                            <th>Brand</th>
                                            <th>Kategori</th>
                                            <th class="text-center" style="width:220px;">Nilai Preferensi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ranking as $i => $row)
                                            @php
                                                $pos = $i + 1;
                                                $badge = match($pos) {
                                                    1 => 'badge-gold',
                                                    2 => 'badge-silver',
                                                    3 => 'badge-bronze',
                                                    default => 'badge-default'
                                                };
                                            @endphp

                                            {{-- ROW UTAMA --}}
                                            <tr class="topsis-row {{ $pos === 1 ? 'top-one-row' : '' }}"
                                                data-detail="detail-{{ $kategori }}-{{ $i }}">
                                                <td class="text-center">
                                                    <span class="rank-badge {{ $badge }}">
                                                        @if($pos === 1)
                                                            <i class="fas fa-crown mr-1"></i>
                                                        @endif
                                                        {{ $pos }}
                                                    </span>
                                                </td>
                                                <td><strong>{{ $row['nama_motor'] }}</strong></td>
                                                <td>{{ $row['brand'] }}</td>
                                                <td>
                                                    <span class="badge badge-light border text-capitalize">
                                                        {{ $row['kategori'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="score-wrapper">
                                                        <div class="score-bar-bg">
                                                            <div class="score-bar-fill"
                                                                 style="width: {{ $row['nilai'] * 100 }}%"></div>
                                                        </div>
                                                        <span class="score-text">
                                                            {{ number_format($row['nilai'], 4) }}
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>

                                            {{-- ROW DETAIL --}}
                                            <tr id="detail-{{ $kategori }}-{{ $i }}" class="topsis-detail-row">
                                                <td colspan="5">
                                                    <div class="topsis-detail-box">
                                                        <div class="detail-grid">
                                                            <div>
                                                                <span class="detail-label">Harga</span>
                                                                <span class="detail-value">
                                                                    Rp {{ number_format($row['harga_asli'],0,',','.') }}
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <span class="detail-label">Kenyamanan</span>
                                                                <span class="detail-value">
                                                                    {{ $row['kenyamanan_asli'] }}/10
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <span class="detail-label">Perawatan</span>
                                                                <span class="detail-value">
                                                                    {{ $row['perawatan_asli'] }}/10
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ===================================================
   TOPSIS – DARK MODERN + SCROLLABLE TABLE (FINAL)
   =================================================== */

.topsis-dark .card {
    background:#0b1220;
    border:1px solid rgba(255,255,255,.06);
    border-radius:1rem;
}

.topsis-dark .card-header {
    background:linear-gradient(180deg,#020617,#0b1220);
}

/* Scroll wrapper */
.topsis-table-wrapper {
    max-height:65vh;
    overflow-y:auto;
    overflow-x:hidden;
    border-radius:12px;
}

/* Sticky header */
.topsis-table-wrapper thead th {
    position:sticky;
    top:0;
    background:#020617;
    z-index:3;
    box-shadow:inset 0 -1px 0 rgba(255,255,255,.08);
}

/* Scrollbar */
.topsis-table-wrapper::-webkit-scrollbar { width:6px; }
.topsis-table-wrapper::-webkit-scrollbar-thumb {
    background:rgba(255,255,255,.15);
    border-radius:6px;
}

/* Table */
.topsis-dark .table { color:#e5e7eb; }
.topsis-dark .table td {
    border-top:1px solid rgba(255,255,255,.06);
    font-size:.85rem;
}

/* Rank badge */
.rank-badge {
    min-width:36px;
    height:28px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border-radius:999px;
    font-size:.8rem;
    font-weight:600;
}

.badge-gold { background:linear-gradient(135deg,#facc15,#eab308); color:#1f2937; }
.badge-silver { background:linear-gradient(135deg,#e5e7eb,#9ca3af); color:#111827; }
.badge-bronze { background:linear-gradient(135deg,#fb923c,#ea580c); color:#111827; }
.badge-default {
    background:rgba(255,255,255,.08);
    color:#e5e7eb;
}

/* Score bar */
.score-wrapper { display:flex; align-items:center; gap:.4rem; }
.score-bar-bg {
    flex:1;
    height:8px;
    background:#020617;
    border-radius:999px;
}
.score-bar-fill {
    height:100%;
    background:linear-gradient(90deg,#2563eb,#22c55e);
}
.score-text { min-width:60px; font-size:.8rem; text-align:right; }

/* Detail dropdown */
.topsis-detail-row { display:none; }
.topsis-detail-box {
    background:#020617;
    border:1px solid rgba(255,255,255,.08);
    border-radius:12px;
    padding:14px;
    animation:slideDown .25s ease;
}

@keyframes slideDown {
    from { opacity:0; transform:translateY(-6px); }
    to { opacity:1; transform:translateY(0); }
}

.detail-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:16px;
}

.detail-label {
    font-size:.7rem;
    color:#94a3b8;
    text-transform:uppercase;
}

.detail-value {
    font-size:.95rem;
    font-weight:600;
    color:#e5e7eb;
}

.topsis-row { cursor:pointer; }
.top-one-row {
    background:linear-gradient(90deg,rgba(250,204,21,.12),transparent);
}
</style>
@endpush

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.topsis-row').forEach(row => {
        row.addEventListener('click', () => {
            const target = document.getElementById(row.dataset.detail);
            document.querySelectorAll('.topsis-detail-row')
                .forEach(el => el !== target && (el.style.display = 'none'));
            target.style.display =
                target.style.display === 'table-row' ? 'none' : 'table-row';
        });
    });
});
</script>
@endsection
