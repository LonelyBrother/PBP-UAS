@extends('layouts.app')

@section('title', 'Tambah Pembeli')

@section('content')
<div class="container-fluid py-4 pembeli-create-dark">
    <div class="card border-0 shadow-sm pembeli-form-card">

        {{-- HEADER --}}
        <div class="card-header border-0 pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
                <div>
                    <h5 class="mb-1 d-flex align-items-center pembeli-form-title">
                        <span class="rounded-circle d-inline-flex justify-content-center align-items-center pembeli-form-icon">
                            <i class="fas fa-user-plus text-primary"></i>
                        </span>
                        <span>Tambah Pembeli</span>
                    </h5>
                    <small class="text-muted pembeli-form-subtitle">
                        Tambahkan data pembeli baru ke dalam sistem.
                    </small>
                </div>

                <a href="{{ route('pembeli.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body pt-3">

            @if ($errors->any())
                <div class="alert alert-danger small border-0 shadow-sm mb-4">
                    <strong>
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Terjadi kesalahan:
                    </strong>
                    <ul class="mb-0 mt-1 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formPembeli" action="{{ route('pembeli.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group mb-3">
                            <label class="form-label pembeli-label">Nama Pembeli</label>
                            <input type="text" name="nama"
                                class="form-control pembeli-input"
                                value="{{ old('nama') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label pembeli-label">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3"
                                    class="form-control pembeli-input"
                                    required>{{ old('alamat') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label pembeli-label">No. Telepon</label>
                            <input type="text" name="telepon"
                                class="form-control pembeli-input"
                                value="{{ old('telepon') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label pembeli-label">Provinsi</label>
                                <select name="provinsi_id" id="provinsi"
                                        class="form-control pembeli-input" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label pembeli-label">Kabupaten / Kota</label>
                                <select name="kabupaten_id" id="kabupaten"
                                        class="form-control pembeli-input"
                                        disabled required>
                                    <option value="">Pilih provinsi terlebih dahulu</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label pembeli-label">Kecamatan</label>
                                <select name="kecamatan_id" id="kecamatan"
                                        class="form-control pembeli-input"
                                        disabled required>
                                    <option value="">Pilih kabupaten terlebih dahulu</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('pembeli.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
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
const provinsi   = document.getElementById('provinsi');
const kabupaten  = document.getElementById('kabupaten');
const kecamatan  = document.getElementById('kecamatan');

// PROVINSI
fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
    .then(res => res.json())
    .then(data => {
        provinsi.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
        data.forEach(item => {
            provinsi.innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
    });

// KABUPATEN
provinsi.addEventListener('change', function () {
    kabupaten.disabled = true;
    kecamatan.disabled = true;

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.value}.json`)
        .then(res => res.json())
        .then(data => {
            kabupaten.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
            data.forEach(item => {
                kabupaten.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
            kabupaten.disabled = false;
        });
});

// KECAMATAN
kabupaten.addEventListener('change', function () {
    kecamatan.disabled = true;

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${this.value}.json`)
        .then(res => res.json())
        .then(data => {
            kecamatan.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            data.forEach(item => {
                kecamatan.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
            kecamatan.disabled = false;
        });
});

// CONFIRM
document.getElementById('formPembeli').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Simpan data pembeli?',
        text: 'Pastikan data pembeli sudah benar.',
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
@endsection

@push('styles')
<style>
/* ===================================================
   PEMBELI CREATE – DARK MODERN THEME
   (CLONE DARI MOTOR.CREATE)
   =================================================== */

.pembeli-create-dark .card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
}

.pembeli-create-dark .card-header {
    background: linear-gradient(180deg, #020617, #0b1220);
}

.pembeli-form-title span:last-child {
    color: #f8fafc;
}

.pembeli-form-subtitle {
    color: #94a3b8;
}

.pembeli-form-icon {
    background: rgba(59,130,246,.15);
    color: #60a5fa;
}

.pembeli-label {
    color: #cbd5f5;
}

.pembeli-input {
    background: #020617;
    border: 1px solid rgba(255,255,255,.12);
    color: #e5e7eb;
}

.pembeli-input:focus {
    background: #020617;
    border-color: #3b82f6;
    box-shadow: 0 0 0 .15rem rgba(59,130,246,.25);
    color: #fff;
}

.pembeli-input:disabled {
    background: #020617;
    color: #64748b;
}

.pembeli-create-dark .btn-primary {
    background: #2563eb;
    border-color: #2563eb;
}

.pembeli-create-dark .btn-outline-secondary {
    border-color: rgba(255,255,255,.15);
    color: #e5e7eb;
}

.pembeli-create-dark .btn-outline-secondary:hover {
    background: rgba(255,255,255,.08);
}

.pembeli-create-dark .alert-danger {
    background: rgba(239,68,68,.12);
    border: 1px solid rgba(239,68,68,.35);
    color: #fecaca;
}
</style>
@endpush
