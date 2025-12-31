@extends('layouts.app')

@section('title', 'Input Bobot Kriteria')

@section('content')
<div class="container-fluid topsis-dark py-4">
    <div class="card border-0 shadow-sm topsis-card">
        {{-- HEADER --}}
        <div class="card-header bg-white border-0 pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
                <div>
                    <h5 class="mb-1 d-flex align-items-center topsis-title">
                        <span class="rounded-circle d-inline-flex justify-content-center align-items-center topsis-icon">
                            <i class="fas fa-balance-scale"></i>
                        </span>
                        <span>Input Bobot Kriteria</span>
                    </h5>
                    <small class="text-muted">
                        Atur prioritas kriteria. Total bobot harus <strong>100%</strong>.
                    </small>
                </div>

                <a href="{{ route('motor.topsis') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-list-ul mr-1"></i>Lihat Hasil Ranking
                </a>
            </div>
        </div>

        <form id="bobotForm" action="{{ route('topsis.hitung') }}" method="POST">
            @csrf
            <div class="card-body pt-3">

                {{-- Row input bobot --}}
                <div class="row">
                    {{-- Harga --}}
                    <div class="col-md-4 mb-3">
                        <div class="bobot-box">
                            <label for="harga" class="bobot-label">
                                <span class="bobot-label-icon bg-soft-primary">
                                    <i class="fas fa-tags"></i>
                                </span>
                                <span>
                                    Harga
                                    <small class="d-block text-muted small">Semakin rendah semakin baik (cost)</small>
                                </span>
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number"
                                       id="harga"
                                       name="harga"
                                       min="0" max="100"
                                       value="{{ old('harga', 33) }}"
                                       oninput="updateTotal()"
                                       class="form-control bobot-input"
                                       placeholder="Bobot harga">
                                <span class="input-group-text border-0 bg-transparent small text-muted">%</span>
                            </div>
                        </div>
                    </div>

                    {{-- Kenyamanan --}}
                    <div class="col-md-4 mb-3">
                        <div class="bobot-box">
                            <label for="kenyamanan" class="bobot-label">
                                <span class="bobot-label-icon bg-soft-success">
                                    <i class="fas fa-couch"></i>
                                </span>
                                <span>
                                    Kenyamanan
                                    <small class="d-block text-muted small">Semakin tinggi semakin baik (benefit)</small>
                                </span>
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number"
                                       id="kenyamanan"
                                       name="kenyamanan"
                                       min="0" max="100"
                                       value="{{ old('kenyamanan', 33) }}"
                                       oninput="updateTotal()"
                                       class="form-control bobot-input"
                                       placeholder="Bobot kenyamanan">
                                <span class="input-group-text border-0 bg-transparent small text-muted">%</span>
                            </div>
                        </div>
                    </div>

                    {{-- Perawatan --}}
                    <div class="col-md-4 mb-3">
                        <div class="bobot-box">
                            <label for="perawatan" class="bobot-label">
                                <span class="bobot-label-icon bg-soft-info">
                                    <i class="fas fa-tools"></i>
                                </span>
                                <span>
                                    Perawatan
                                    <small class="d-block text-muted small">Kemudahan & biaya perawatan (benefit)</small>
                                </span>
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number"
                                       id="perawatan"
                                       name="perawatan"
                                       min="0" max="100"
                                       value="{{ old('perawatan', 34) }}"
                                       oninput="updateTotal()"
                                       class="form-control bobot-input"
                                       placeholder="Bobot perawatan">
                                <span class="input-group-text border-0 bg-transparent small text-muted">%</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Bobot --}}
                <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
                    <div class="d-flex align-items-center" style="gap:.5rem;">
                        <span class="small text-muted">Total Bobot</span>
                        <span id="totalWrapper" class="badge badge-pill total-badge-ok">
                            <span id="totalBobot">100</span>%
                        </span>
                    </div>
                    <small class="text-muted">
                        Disarankan: <strong>Harga</strong> tidak terlalu dominan agar kenyamanan & perawatan tetap berpengaruh.
                    </small>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="card-footer topsis-footer d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-calculator mr-1"></i> Hitung Ranking
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateTotal() {
        const harga      = parseInt(document.getElementById('harga').value) || 0;
        const kenyamanan = parseInt(document.getElementById('kenyamanan').value) || 0;
        const perawatan  = parseInt(document.getElementById('perawatan').value) || 0;

        const total = harga + kenyamanan + perawatan;
        const totalSpan   = document.getElementById('totalBobot');
        const wrapper     = document.getElementById('totalWrapper');

        totalSpan.innerText = total;

        // ubah warna badge kalau tidak 100
        if (total === 100) {
            wrapper.classList.remove('total-badge-error');
            wrapper.classList.add('total-badge-ok');
        } else {
            wrapper.classList.remove('total-badge-ok');
            wrapper.classList.add('total-badge-error');
        }
    }

    document.getElementById('bobotForm').addEventListener('submit', function(e) {
        updateTotal();
        const total = parseInt(document.getElementById('totalBobot').innerText);

        if (total !== 100) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Total bobot tidak valid!',
                text: `Total bobot harus 100%. Sekarang: ${total}%`
            });
        }
    });

    // inisialisasi di awal
    updateTotal();
</script>

@push('styles')
<style>
/* ===================================================
   TOPSIS – INPUT BOBOT (DARK MODERN)
   =================================================== */

.topsis-dark .card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
    border-radius: 1rem;
}

/* Header */
.topsis-dark .card-header {
    background: linear-gradient(180deg, #020617, #0b1220);
    border-bottom: 1px solid rgba(255,255,255,.06);
}

/* Title */
.topsis-dark .topsis-title span:last-child {
    color: #f8fafc;
}

.topsis-dark .text-muted {
    color: #94a3b8 !important;
}

/* Icon */
.topsis-dark .topsis-icon {
    background: rgba(99,102,241,.15);
    color: #818cf8;
}

/* Bobot box */
.bobot-box {
    background: #020617;
    border-radius: .85rem;
    padding: .9rem 1rem;
    border: 1px solid rgba(255,255,255,.08);
}

/* Label */
.bobot-label {
    display:flex;
    align-items:flex-start;
    gap:.6rem;
    font-weight:600;
    font-size:.85rem;
    color:#e5e7eb;
}

/* Icon */
.bobot-label-icon {
    width:30px;
    height:30px;
    border-radius:999px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:.8rem;
    color:#e5e7eb;
}

.bg-soft-primary { background: rgba(59,130,246,.2); }
.bg-soft-success { background: rgba(16,185,129,.2); }
.bg-soft-info    { background: rgba(14,165,233,.2); }

/* Input */
.bobot-input {
    background:#020617;
    border:1px solid rgba(255,255,255,.12);
    color:#e5e7eb;
    font-size:.86rem;
    border-radius:.6rem;
}

.bobot-input::placeholder {
    color:#64748b;
}

.bobot-input:focus {
    background:#020617;
    border-color:#6366f1;
    box-shadow:0 0 0 .15rem rgba(99,102,241,.25);
}

/* Total badge */
.total-badge-ok {
    background: rgba(34,197,94,.15);
    color:#22c55e;
    border:1px solid rgba(34,197,94,.35);
}

.total-badge-error {
    background: rgba(239,68,68,.15);
    color:#ef4444;
    border:1px solid rgba(239,68,68,.35);
}

.total-badge-ok,
.total-badge-error {
    font-size:.78rem;
    padding:.25rem .7rem;
}

/* Footer */
.topsis-dark .card-footer {
    background: transparent;
    border-top: 1px solid rgba(255,255,255,.06);
}

/* Button */
.topsis-dark .btn-primary {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    border:none;
}

/* ================= FOOTER ================= */
.topsis-footer {
    background: linear-gradient(180deg, #020617, #0b1220);
    border-top: 1px solid rgba(255,255,255,.06);
    padding: .75rem 1rem;
}

/* Button tweak */
.topsis-footer .btn-primary {
    font-size: .85rem;
    font-weight: 500;
    border-radius: .6rem;
}

</style>
@endpush
@endsection
