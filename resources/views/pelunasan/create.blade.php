@extends('layouts.app')

@section('title', 'Tambah Data Pelunasan')

@section('content')
<div class="container-fluid py-4 pelunasan-create-dark">

    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header border-0 pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
                <div>
                    <h5 class="mb-1 d-flex align-items-center page-title">
                        <span class="icon-circle">
                            <i class="fas fa-file-invoice-dollar text-primary"></i>
                        </span>
                        <span>Tambah Data Pelunasan</span>
                    </h5>
                    <small class="page-subtitle">
                        Catatan pelunasan pembelian motor.
                    </small>
                </div>

                <a href="{{ route('pelunasan.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body pt-3">

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="alert alert-danger small border-0 shadow-sm mb-4">
                    <strong>
                        <i class="fas fa-exclamation-circle mr-1"></i>Terjadi kesalahan:
                    </strong>
                    <ul class="mb-0 mt-1 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <form id="formPelunasan" action="{{ route('pelunasan.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label pelunasan-label">No Pelunasan</label>
                        <input type="text"
                               name="no_pelunasan"
                               class="form-control pelunasan-input"
                               value="{{ old('no_pelunasan') }}"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label pelunasan-label">Pembeli</label>
                        <select name="pembeli_id" class="form-control pelunasan-input" required>
                            <option value="">-- Pilih Pembeli --</option>
                            @foreach($pembeli as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('pembeli_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label pelunasan-label">Status</label>
                        <select name="status" class="form-control pelunasan-input" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Lunas" {{ old('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="Belum Lunas" {{ old('status') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label pelunasan-label">Tanggal Pelunasan</label>
                        <input type="date"
                               name="tanggal_pelunasan"
                               class="form-control pelunasan-input pelunasan-date"
                               value="{{ old('tanggal_pelunasan', date('Y-m-d')) }}"
                               required>
                    </div>
                </div>

                {{-- DAFTAR MOTOR --}}
                <div class="mb-3">
                    <label class="form-label pelunasan-label">Daftar Motor</label>

                    <div class="pelunasan-motor-box">
                        @php
                            $selectedMotor = collect(old('daftar_motor', []));
                        @endphp

                        @foreach($motor as $m)
                            <div class="form-check pelunasan-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="daftar_motor[]"
                                       value="{{ $m->nama_motor }}"
                                       id="motor{{ $loop->index }}"
                                       {{ $selectedMotor->contains($m->nama_motor) ? 'checked' : '' }}>
                                <label class="form-check-label" for="motor{{ $loop->index }}">
                                    {{ $m->nama_motor }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    @error('daftar_motor')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-undo mr-1"></i>Reset
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('formPelunasan').addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Simpan Data Pelunasan?',
        text: 'Pastikan semua data sudah benar.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) this.submit();
    });
});
</script>
@endsection

@push('styles')
<style>
/* ===================================================
   PELUNASAN CREATE – DARK MODERN STYLE (DATE FIX)
   =================================================== */

.pelunasan-create-dark .card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
}

.pelunasan-create-dark .card-header {
    background: linear-gradient(180deg, #020617, #0b1220);
}

/* Label */
.pelunasan-label {
    color: #cbd5f5;
    font-size: .85rem;
}

/* Input */
.pelunasan-input {
    background: #020617;
    border: 1px solid rgba(255,255,255,.12);
    color: #e5e7eb;
}

.pelunasan-input:focus {
    background: #020617;
    border-color: #3b82f6;
    box-shadow: 0 0 0 .15rem rgba(59,130,246,.25);
}

/* DATE PICKER FIX */
.pelunasan-date {
    pointer-events: auto !important;
    cursor: pointer !important;
}

.pelunasan-date::-webkit-calendar-picker-indicator {
    filter: invert(1);
    cursor: pointer;
}

/* Motor checklist */
.pelunasan-motor-box {
    background: #020617;
    border: 1px solid rgba(255,255,255,.08);
    border-radius: .5rem;
    padding: .75rem;
}

.pelunasan-check {
    margin-bottom: .4rem;
}

.pelunasan-check label {
    color: #e5e7eb;
}

/* Alert */
.pelunasan-create-dark .alert-danger {
    background: rgba(239,68,68,.12);
    border: 1px solid rgba(239,68,68,.35);
    color: #fecaca;
}
</style>
@endpush
