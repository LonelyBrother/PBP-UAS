@extends('layouts.app')

@section('title', 'Edit Motor')

@php
    $kategori = strtolower($motor->kategori);
@endphp

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm motor-form-card">

        {{-- HEADER --}}
        <div class="card-header border-0 pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-1 d-flex align-items-center motor-form-title">
                        <span class="rounded-circle d-inline-flex justify-content-center align-items-center motor-form-icon">
                            <i class="fas fa-motorcycle text-success"></i>
                        </span>
                        <span>Edit Motor</span>
                    </h5>
                    <small class="text-muted motor-form-subtitle">
                        Perbarui informasi motor yang sudah terdaftar.
                    </small>
                </div>

                <a href="{{ route('motor.index', ['kategori' => $kategori]) }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>
        </div>

        <div class="card-body pt-3">

            {{-- ERROR VALIDASI --}}
            @if ($errors->any())
                <div class="alert alert-danger small border-0 shadow-sm mb-4">
                    <strong><i class="fas fa-exclamation-circle mr-1"></i>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-1 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 🔥 ACTION FIX DI SINI --}}
            <form id="formEditMotor"
                action="{{ route('motor.update', $motor->id) }}?kategori={{ $kategori }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    {{-- KOLOM KIRI --}}
                    <div class="col-md-7">

                        <div class="form-group mb-3">
                            <label class="form-label motor-label">Nama Motor</label>
                            <input type="text"
                                   name="nama_motor"
                                   class="form-control motor-input"
                                   value="{{ old('nama_motor', $motor->nama_motor) }}"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label motor-label">Brand</label>
                            <input type="text"
                                   name="brand"
                                   class="form-control motor-input"
                                   value="{{ old('brand', $motor->brand) }}"
                                   required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label motor-label">Kategori</label>
                                <select name="kategori" class="form-control motor-input">
                                    <option value="sport"   {{ old('kategori', $motor->kategori)=='sport'?'selected':'' }}>Sport</option>
                                    <option value="matic"   {{ old('kategori', $motor->kategori)=='matic'?'selected':'' }}>Matic</option>
                                    <option value="listrik" {{ old('kategori', $motor->kategori)=='listrik'?'selected':'' }}>Listrik</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label motor-label">Harga (Rp)</label>
                                <input type="number"
                                       name="harga"
                                       class="form-control motor-input"
                                       value="{{ old('harga', $motor->harga) }}"
                                       required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label motor-label">Kenyamanan (1–10)</label>
                                <input type="number"
                                       name="kenyamanan"
                                       min="1" max="10"
                                       class="form-control motor-input"
                                       value="{{ old('kenyamanan', $motor->kenyamanan) }}"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label motor-label">Perawatan (1–10)</label>
                                <input type="number"
                                       name="perawatan"
                                       min="1" max="10"
                                       class="form-control motor-input"
                                       value="{{ old('perawatan', $motor->perawatan) }}"
                                       required>
                            </div>
                        </div>

                    </div>

                    {{-- KOLOM KANAN --}}
                    <div class="col-md-5">
                        <div class="border rounded-3 p-3 motor-upload-box h-100">
                            <label class="form-label motor-label">Gambar Motor</label>

                            <div class="motor-preview rounded mb-3">
                                @if($motor->gambar)
                                    <img id="previewImage"
                                         src="{{ asset('storage/'.$motor->gambar) }}"
                                         alt="Preview">
                                @else
                                    <span id="previewPlaceholder" class="text-muted small">
                                        Belum ada gambar
                                    </span>
                                    <img id="previewImage" style="display:none;">
                                @endif
                            </div>

                            <input type="file"
                                   name="gambar"
                                   class="form-control motor-input"
                                   accept="image/*"
                                   onchange="previewMotorImage(event)">

                            <small class="text-muted d-block mt-2">
                                Kosongkan jika tidak ingin mengganti gambar.
                            </small>
                        </div>
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success btn-dark-success px-4">
                        <i class="fas fa-save mr-1"></i>Update
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewMotorImage(event) {
        const file   = event.target.files[0];
        const img    = document.getElementById('previewImage');
        const holder = document.getElementById('previewPlaceholder');

        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            img.style.display = 'block';
            if (holder) holder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    // SWEETALERT CONFIRM UPDATE
    document.getElementById('formEditMotor').addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Simpan perubahan?',
            text: 'Data motor akan diperbarui.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, update',
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
   MOTOR EDIT – DARK MODERN THEME
   =================================================== */

.motor-edit-dark .card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
    border-radius: 1rem;
}

/* Header */
.motor-edit-dark .card-header {
    background: linear-gradient(180deg, #020617, #0b1220);
}

/* Title */
.motor-edit-dark .motor-form-title span:last-child {
    color: #f8fafc;
}

.motor-edit-dark .motor-form-subtitle {
    color: #94a3b8;
}

/* Icon */
.motor-edit-dark .motor-form-icon {
    width: 32px;
    height: 32px;
    background: rgba(34,197,94,.15);
    color: #22c55e;
    margin-right: .5rem;
}

/* Label */
.motor-edit-dark .motor-label {
    color: #cbd5f5;
    font-size: .82rem;
    font-weight: 600;
}

/* Input */
.motor-edit-dark .motor-input {
    background: #020617;
    border: 1px solid rgba(255,255,255,.12);
    color: #e5e7eb;
    border-radius: .55rem;
    font-size: .86rem;
}

.motor-edit-dark .motor-input:focus {
    background: #020617;
    border-color: #22c55e;
    box-shadow: 0 0 0 .15rem rgba(34,197,94,.25);
    color: #fff;
}

/* Upload box */
.motor-edit-dark .motor-upload-box {
    background: #020617;
    border: 1px solid rgba(255,255,255,.08);
}

/* Preview */
.motor-edit-dark .motor-preview {
    width: 100%;
    max-width: 220px;
    height: 150px;
    background: rgba(255,255,255,.04);
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
}

.motor-edit-dark .motor-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Buttons */
.motor-edit-dark .btn-outline-secondary {
    border-color: rgba(255,255,255,.15);
    color: #e5e7eb;
}

.motor-edit-dark .btn-outline-secondary:hover {
    background: rgba(255,255,255,.08);
}

/* Dark success button */
.motor-edit-dark .btn-dark-success {
    background: #16a34a;
    border-color: #16a34a;
}

.motor-edit-dark .btn-dark-success:hover {
    background: #15803d;
    border-color: #15803d;
}

/* Error alert */
.motor-edit-dark .alert-danger {
    background: rgba(239,68,68,.12);
    border: 1px solid rgba(239,68,68,.35);
    color: #fecaca;
}
</style>
@endpush
