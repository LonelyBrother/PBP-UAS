@extends('layouts.app')

@section('title', 'Edit Pembeli')

@section('content')
<div class="container-fluid py-4 pembeli-edit-dark">
    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header border-0 pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">

                <div>
                    <h5 class="mb-1 d-flex align-items-center pembeli-edit-title">
                        <span class="rounded-circle d-inline-flex justify-content-center align-items-center pembeli-edit-icon">
                            <i class="fas fa-edit text-warning"></i>
                        </span>
                        <span>Edit Pembeli</span>
                    </h5>
                    <small class="text-muted pembeli-edit-subtitle">
                        Perbarui data pembeli yang sudah terdaftar.
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

            {{-- Error validation --}}
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

            <form id="formPembeliEdit"
                  action="{{ route('pembeli.update', $pembeli->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group mb-3">
                            <label class="form-label pembeli-label">Nama Pembeli</label>
                            <input type="text" name="nama"
                                   class="form-control pembeli-input"
                                   value="{{ old('nama', $pembeli->nama) }}"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label pembeli-label">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3"
                                      class="form-control pembeli-input"
                                      required>{{ old('alamat', $pembeli->alamat) }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label pembeli-label">No. Telepon</label>
                            <input type="text" name="telepon"
                                   class="form-control pembeli-input"
                                   value="{{ old('telepon', $pembeli->telepon) }}"
                                   required>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label pembeli-label">Provinsi</label>
                                <select name="provinsi_id" id="provinsi"
                                        class="form-control pembeli-input" required>
                                    <option value="">Memuat data provinsi...</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label pembeli-label">Kabupaten / Kota</label>
                                <select name="kabupaten_id" id="kabupaten"
                                        class="form-control pembeli-input"
                                        required disabled>
                                    <option value="">Pilih provinsi terlebih dahulu</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label pembeli-label">Kecamatan</label>
                                <select name="kecamatan_id" id="kecamatan"
                                        class="form-control pembeli-input"
                                        required disabled>
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
const provinsiSelect  = document.getElementById('provinsi');
const kabupatenSelect = document.getElementById('kabupaten');
const kecamatanSelect = document.getElementById('kecamatan');

const selectedProvinsiId  = "{{ old('provinsi_id', $pembeli->provinsi_id) }}";
const selectedKabupatenId = "{{ old('kabupaten_id', $pembeli->kabupaten_id) }}";
const selectedKecamatanId = "{{ old('kecamatan_id', $pembeli->kecamatan_id) }}";

function loadProvinsi() {
    fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
        .then(res => res.json())
        .then(data => {
            provinsiSelect.innerHTML = `<option value="">-- Pilih Provinsi --</option>`;
            data.forEach(item => {
                provinsiSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });

            if (selectedProvinsiId) {
                provinsiSelect.value = selectedProvinsiId;
                loadKabupaten(selectedProvinsiId);
            }
        });
}

function loadKabupaten(provinsiId) {
    kabupatenSelect.innerHTML = `<option value="">Memuat data...</option>`;
    kabupatenSelect.disabled = true;
    kecamatanSelect.disabled = true;

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinsiId}.json`)
        .then(res => res.json())
        .then(data => {
            kabupatenSelect.innerHTML = `<option value="">-- Pilih Kabupaten --</option>`;
            data.forEach(item => {
                kabupatenSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
            kabupatenSelect.disabled = false;

            if (selectedKabupatenId) {
                kabupatenSelect.value = selectedKabupatenId;
                loadKecamatan(selectedKabupatenId);
            }
        });
}

function loadKecamatan(kabupatenId) {
    kecamatanSelect.innerHTML = `<option value="">Memuat data...</option>`;
    kecamatanSelect.disabled = true;

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kabupatenId}.json`)
        .then(res => res.json())
        .then(data => {
            kecamatanSelect.innerHTML = `<option value="">-- Pilih Kecamatan --</option>`;
            data.forEach(item => {
                kecamatanSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
            kecamatanSelect.disabled = false;

            if (selectedKecamatanId) {
                kecamatanSelect.value = selectedKecamatanId;
            }
        });
}

provinsiSelect.addEventListener('change', function () {
    if (this.value) loadKabupaten(this.value);
});

kabupatenSelect.addEventListener('change', function () {
    if (this.value) loadKecamatan(this.value);
});

loadProvinsi();

// CONFIRM UPDATE
document.getElementById('formPembeliEdit').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Update data pembeli?',
        text: 'Perubahan akan disimpan ke sistem.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, update',
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
   PEMBELI EDIT – DARK MODERN THEME
   =================================================== */

.pembeli-edit-dark .card {
    background: #0b1220;
    border: 1px solid rgba(255,255,255,.06);
}

.pembeli-edit-dark .card-header {
    background: linear-gradient(180deg, #020617, #0b1220);
}

.pembeli-edit-title span:last-child {
    color: #f8fafc;
}

.pembeli-edit-subtitle {
    color: #94a3b8;
}

.pembeli-edit-icon {
    background: rgba(251,191,36,.18);
    color: #fbbf24;
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

.pembeli-edit-dark .btn-primary {
    background: #2563eb;
    border-color: #2563eb;
}

.pembeli-edit-dark .btn-outline-secondary {
    border-color: rgba(255,255,255,.15);
    color: #e5e7eb;
}

.pembeli-edit-dark .btn-outline-secondary:hover {
    background: rgba(255,255,255,.08);
}

.pembeli-edit-dark .alert-danger {
    background: rgba(239,68,68,.12);
    border: 1px solid rgba(239,68,68,.35);
    color: #fecaca;
}
</style>
@endpush
