@extends('layouts.app')

@section('title', 'Edit Data Pelunasan')

@section('content')
<div class="container-fluid py-4 pelunasan-edit-dark">

    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header border-0 pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
                <div>
                    <h5 class="mb-1 d-flex align-items-center page-title">
                        <span class="icon-circle">
                            <i class="fas fa-file-invoice-dollar text-primary"></i>
                        </span>
                        <span>Edit Data Pelunasan</span>
                    </h5>
                    <small class="page-subtitle">
                        Perbarui data pelunasan pembelian motor.
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
            <form id="formPelunasan"
                  method="POST"
                  action="{{ route('pelunasan.update', $pelunasan->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- NO PELUNASAN (READONLY, NORMAL LOOK) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label pelunasan-label">No Pelunasan</label>
                        <input type="text"
                               class="form-control pelunasan-input pelunasan-readonly"
                               value="{{ $pelunasan->no_pelunasan }}"
                               readonly>
                    </div>

                    {{-- PEMBELI --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label pelunasan-label">Pembeli</label>
                        <select name="pembeli_id"
                                class="form-control pelunasan-input"
                                required>
                            <option value="">-- Pilih Pembeli --</option>
                            @foreach($pembeli as $p)
                                <option value="{{ $p->id }}"
                                    {{ $pelunasan->pembeli_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    {{-- STATUS --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label pelunasan-label">Status</label>
                        <select name="status"
                                class="form-control pelunasan-input"
                                required>
                            <option value="Lunas" {{ $pelunasan->status === 'Lunas' ? 'selected' : '' }}>
                                Lunas
                            </option>
                            <option value="Belum Lunas" {{ $pelunasan->status === 'Belum Lunas' ? 'selected' : '' }}>
                                Belum Lunas
                            </option>
                        </select>
                    </div>

                    {{-- TANGGAL (EDITABLE) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label pelunasan-label">Tanggal Pelunasan</label>
                        <input type="date"
                               name="tanggal_pelunasan"
                               class="form-control pelunasan-input pelunasan-date"
                               value="{{ optional($pelunasan->tanggal_pelunasan)->format('Y-m-d') }}"
                               required>
                    </div>
                </div>

                {{-- DAFTAR MOTOR --}}
                <div class="mb-3">
                    <label class="form-label pelunasan-label">Daftar Motor</label>

                    <div class="pelunasan-motor-box">
                        @php
                            $selectedMotor = is_array($pelunasan->daftar_motor)
                                ? $pelunasan->daftar_motor
                                : (json_decode($pelunasan->daftar_motor, true) ?? []);
                        @endphp

                        @foreach($motor as $m)
                            <div class="form-check pelunasan-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="daftar_motor[]"
                                       value="{{ $m->nama_motor }}"
                                       id="motor{{ $loop->index }}"
                                       {{ in_array($m->nama_motor, $selectedMotor) ? 'checked' : '' }}>
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
                    <a href="{{ route('pelunasan.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i>Simpan Perubahan
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
        title: 'Simpan Perubahan?',
        text: 'Data pelunasan akan diperbarui.',
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
   PELUNASAN EDIT – DARK MODERN STYLE (CLEAN FINAL)
   =================================================== */

.pelunasan-edit-dark .card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
}

.pelunasan-edit-dark .card-header {
    background: linear-gradient(180deg, #020617, #0b1220);
}

/* Label */
.pelunasan-label {
    color: #cbd5f5;
    font-size: .85rem;
}

/* Input base */
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

/* READONLY (NORMAL LOOK, NOT DISABLED) */
.pelunasan-readonly {
    background: #020617 !important;
    color: #e5e7eb !important;
    border: 1px solid rgba(255,255,255,.12);
}

/* DATE INPUT FIX */
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
.pelunasan-edit-dark .alert-danger {
    background: rgba(239,68,68,.12);
    border: 1px solid rgba(239,68,68,.35);
    color: #fecaca;
}
</style>
@endpush
