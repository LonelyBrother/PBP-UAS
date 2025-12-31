@extends('layouts.app')

@section('title', 'Tambah Motor')

@section('content')
<div class="container-fluid py-4 motor-create-dark">
    <div class="card border-0 shadow-sm motor-form-card">

        {{-- HEADER --}}
        <div class="card-header border-0 pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
                <div>
                    <h5 class="mb-1 d-flex align-items-center motor-form-title">
                        <span class="rounded-circle d-inline-flex justify-content-center align-items-center motor-form-icon">
                            <i class="fas fa-motorcycle text-primary"></i>
                        </span>
                        <span>Tambah Motor</span>
                    </h5>
                    <small class="text-muted motor-form-subtitle">
                        Isi detail motor untuk dimasukkan ke dalam data.
                    </small>
                </div>

                <a href="{{ route('motor.index', ['kategori' => request('kategori','all')]) }}"
                   class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body pt-3">

            {{-- Error validation --}}
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

            <form id="formMotor"
                  action="{{ route('motor.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- KIRI --}}
                    <div class="col-md-7">

                        <div class="form-group mb-3">
                            <label class="form-label motor-label">Nama Motor</label>
                            <input type="text" name="nama_motor"
                                   class="form-control motor-input"
                                   value="{{ old('nama_motor') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label motor-label">Brand</label>
                            <input type="text" name="brand"
                                   class="form-control motor-input"
                                   value="{{ old('brand') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label motor-label">Kategori</label>
                                <select name="kategori" class="form-control motor-input" required>
                                    <option value="">Pilih kategori</option>
                                    <option value="sport" {{ old('kategori')=='sport'?'selected':'' }}>Sport</option>
                                    <option value="matic" {{ old('kategori')=='matic'?'selected':'' }}>Matic</option>
                                    <option value="listrik" {{ old('kategori')=='listrik'?'selected':'' }}>Listrik</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label motor-label">Harga (Rp)</label>
                                <input type="number" name="harga"
                                       class="form-control motor-input"
                                       value="{{ old('harga') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label motor-label">Kenyamanan (1–10)</label>
                                <input type="number" name="kenyamanan" min="1" max="10"
                                       class="form-control motor-input"
                                       value="{{ old('kenyamanan') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label motor-label">Perawatan (1–10)</label>
                                <input type="number" name="perawatan" min="1" max="10"
                                       class="form-control motor-input"
                                       value="{{ old('perawatan') }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN --}}
                    <div class="col-md-5">
                        <div class="border rounded-3 p-3 h-100 motor-upload-box">
                            <label class="form-label motor-label">Gambar Motor</label>

                            <div class="motor-preview rounded mb-2 text-muted">
                                <span id="previewPlaceholder">Preview gambar</span>
                                <img id="previewImage" style="display:none;">
                            </div>

                            <input type="file" name="gambar"
                                   class="form-control motor-input"
                                   accept="image/*"
                                   onchange="previewMotorImage(event)"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('motor.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
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
    // preview image
    function previewMotorImage(e) {
        const img = document.getElementById('previewImage');
        const holder = document.getElementById('previewPlaceholder');
        const file = e.target.files[0];

        if (!file) return;

        const reader = new FileReader();
        reader.onload = ev => {
            img.src = ev.target.result;
            img.style.display = 'block';
            holder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    // SweetAlert confirm submit
    document.getElementById('formMotor').addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Simpan data motor?',
            text: 'Pastikan data motor sudah benar.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>

@push('styles')
<style>
/* ===================================================
   MOTOR CREATE – DARK MODERN THEME
   =================================================== */

.motor-create-dark .card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
}

/* Header */
.motor-create-dark .card-header {
    background: linear-gradient(180deg, #020617, #0b1220);
}

/* Title */
.motor-create-dark .motor-form-title span:last-child {
    color: #f8fafc;
}

.motor-create-dark .motor-form-subtitle {
    color: #94a3b8;
}

/* Icon */
.motor-create-dark .motor-form-icon {
    background: rgba(59,130,246,.15);
    color: #60a5fa;
}

/* Form label */
.motor-create-dark .motor-label {
    color: #cbd5f5;
}

/* Input */
.motor-create-dark .motor-input {
    background: #020617;
    border: 1px solid rgba(255,255,255,.12);
    color: #e5e7eb;
}

.motor-create-dark .motor-input:focus {
    background: #020617;
    border-color: #3b82f6;
    box-shadow: 0 0 0 .15rem rgba(59,130,246,.25);
    color: #fff;
}

/* Upload box */
.motor-create-dark .motor-upload-box {
    background: #020617;
    border: 1px solid rgba(255,255,255,.08);
}

/* Preview */
.motor-create-dark .motor-preview {
    background: rgba(255,255,255,.04);
    color: #94a3b8;
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.motor-create-dark .motor-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Buttons */
.motor-create-dark .btn-primary {
    background: #2563eb;
    border-color: #2563eb;
}

.motor-create-dark .btn-outline-secondary {
    border-color: rgba(255,255,255,.15);
    color: #e5e7eb;
}

.motor-create-dark .btn-outline-secondary:hover {
    background: rgba(255,255,255,.08);
}

/* Alert error */
.motor-create-dark .alert-danger {
    background: rgba(239,68,68,.12);
    border: 1px solid rgba(239,68,68,.35);
    color: #fecaca;
}
</style>
@endpush

@endsection
