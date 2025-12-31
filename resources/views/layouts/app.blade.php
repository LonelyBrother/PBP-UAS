<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistem Pemilihan Motor')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ADMINLTE CORE --}}
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    {{-- ================= MODERN STYLE (NON-DESTRUCTIVE) ================= --}}
    <style>
        body { font-size: .9rem; }

        .card {
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0,0,0,.04);
        }

        .page-title {
            font-size: 1.15rem;
            font-weight: 600;
        }

        .page-subtitle {
            font-size: .8rem;
            color: #6c757d;
        }

        .icon-circle {
            width: 34px;
            height: 34px;
            background: rgba(0,123,255,.08);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: .5rem;
        }

        table thead th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6c757d;
        }

        table tbody td {
            font-size: .86rem;
            vertical-align: middle;
        }

        .btn-circle {
            width: 32px;
            height: 32px;
            padding: 0;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .form-control {
            font-size: .86rem;
            border-radius: .55rem;
        }

        .nav-sidebar .nav-link {
            border-radius: .5rem;
            margin-bottom: 2px;
        }

        .nav-sidebar .nav-link.active {
            background: rgba(255,255,255,.1);
        }

        .brand-link {
            font-weight: 500;
            font-size: .95rem;
        }

                /* ================= SIDEBAR MODERN ENHANCEMENT ================= */

        /* Sidebar background */
        .main-sidebar {
            background: linear-gradient(180deg, #1f2937, #111827);
        }

        /* Brand */
        .brand-link {
            border-bottom: 1px solid rgba(255,255,255,.08);
            padding: .9rem 1rem;
        }

        .brand-text {
            font-weight: 600;
            letter-spacing: .02em;
        }

        /* Sidebar padding */
        .sidebar {
            padding: .75rem;
        }

        /* Header section */
        .nav-header {
            font-size: .65rem;
            letter-spacing: .12em;
            color: rgba(255,255,255,.45);
            margin: 1rem .5rem .4rem;
        }

        /* Nav item base */
        .nav-sidebar .nav-item > .nav-link {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem .75rem;
            border-radius: .6rem;
            color: rgba(255,255,255,.85);
            transition: all .2s ease;
        }

        /* Icon */
        .nav-sidebar .nav-icon {
            font-size: .9rem;
            opacity: .9;
        }

        /* Hover */
        .nav-sidebar .nav-link:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
        }

        /* Active */
        .nav-sidebar .nav-link.active {
            background: rgba(59,130,246,.25);
            color: #fff;
            font-weight: 500;
        }

        /* Treeview children */
        .nav-treeview {
            margin-left: .3rem;
            padding-left: .3rem;
            border-left: 1px dashed rgba(255,255,255,.12);
        }

        .nav-treeview .nav-link {
            font-size: .82rem;
            padding: .45rem .7rem;
        }

        /* Active child */
        .nav-treeview .nav-link.active {
            background: rgba(59,130,246,.18);
        }

        /* Arrow icon */
        .nav-sidebar .right {
            margin-left: auto;
            opacity: .7;
        }

        /* Sidebar collapse fix */
        .sidebar-mini.sidebar-collapse .nav-sidebar .nav-link p {
            display: none;
        }

        /* ==========================================================
        GLOBAL DARK MODERN THEME
        ========================================================== */

        body.dark-mode {
            background-color: #0f172a;
            color: #e5e7eb;
        }

        /* ================= CARD ================= */
        .card {
            background: #111827;
            border: 1px solid rgba(255,255,255,.05);
            box-shadow: 0 8px 20px rgba(0,0,0,.35);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        .card-footer {
            border-top: 1px solid rgba(255,255,255,.06);
        }

        /* ================= TITLE ================= */
        .page-title {
            color: #f9fafb;
        }

        .page-subtitle {
            color: #9ca3af;
        }

        /* ================= TABLE ================= */
        .table {
            color: #e5e7eb;
        }

        .table thead {
            background: #0b1220;
        }

        .table thead th {
            color: #9ca3af;
            font-size: .7rem;
            letter-spacing: .08em;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(255,255,255,.05);
        }

        .table tbody tr:hover {
            background: rgba(255,255,255,.04);
        }

        .table td {
            font-size: .85rem;
        }

        /* ================= BADGE ================= */
        .badge-light {
            background: rgba(255,255,255,.08);
            color: #e5e7eb;
        }

        /* ================= BUTTON ================= */
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border: none;
        }

        .btn-success {
            background: linear-gradient(135deg, #059669, #10b981);
            border: none;
        }

        .btn-outline-secondary {
            color: #9ca3af;
            border-color: rgba(255,255,255,.2);
        }

        .btn-outline-secondary:hover {
            background: rgba(255,255,255,.08);
        }

        /* Circle buttons */
        .btn-circle {
            width: 30px;
            height: 30px;
            border-radius: 999px;
        }

        /* ================= FORM ================= */
        .form-control,
        .form-select {
            background: #0b1220;
            border: 1px solid rgba(255,255,255,.1);
            color: #e5e7eb;
        }

        .form-control:focus,
        .form-select:focus {
            background: #0b1220;
            border-color: #3b82f6;
            box-shadow: 0 0 0 .15rem rgba(59,130,246,.25);
        }

        .form-label {
            color: #cbd5f5;
        }

        /* ================= ALERT ================= */
        .alert {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.08);
            color: #e5e7eb;
        }

        /* ================= IMAGE PREVIEW ================= */
        .motor-image-wrapper,
        .motor-preview {
            background: #020617;
            border: 1px dashed rgba(255,255,255,.15);
        }

        /* ================= PAGINATION ================= */
        .pagination .page-link {
            background: #020617;
            color: #cbd5f5;
            border: 1px solid rgba(255,255,255,.1);
        }

        .pagination .page-item.active .page-link {
            background: #2563eb;
            border-color: #2563eb;
        }

        /* ================= SCROLLBAR ================= */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,.15);
            border-radius: 6px;
        }

        /* ================= GLOBAL DARK FIX ================= */

            body.dark-mode {
                background-color: #020617;
                color: #e5e7eb;
            }

            .content-wrapper {
                background: #020617 !important;
            }

            .content-header h1 {
                color: #f8fafc;
            }

            /* text */
            .text-muted {
                color: #94a3b8 !important;
            }

            /* table */
            .table {
                color: #e5e7eb;
            }

            /* form */
            .form-control,
            .form-select {
                background-color: #020617;
                border: 1px solid rgba(255,255,255,.1);
                color: #e5e7eb;
            }

            .form-control::placeholder {
                color: #64748b;
            }

            /* dropdown */
            .dropdown-menu {
                background-color: #020617;
                border: 1px solid rgba(255,255,255,.1);
            }

            .dropdown-item {
                color: #e5e7eb;
            }

            .dropdown-item:hover {
                background: rgba(255,255,255,.08);
            }

            /* ===================================================
            FOOTER – DARK MODERN
            =================================================== */

            .footer-dark {
                background: linear-gradient(180deg, #020617, #020617);
                border-top: 1px solid rgba(255,255,255,.06);
                color: #94a3b8;
                padding: .75rem 1rem;
            }

            .footer-dark strong {
                color: #e5e7eb;
                font-weight: 500;
            }

            /* Right badge */
            .footer-badge {
                background: rgba(59,130,246,.15);
                color: #93c5fd;
                border: 1px solid rgba(59,130,246,.35);
                font-size: .7rem;
                padding: .25rem .6rem;
                border-radius: 999px;
                letter-spacing: .05em;
            }
    </style>

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

@php
    $user = Auth::user();
    $isAdmin = $user && (
        ($user->role ?? null) === 'admin' ||
        ($user->Level ?? null) === 'admin'
    );
@endphp

{{-- ================= NAVBAR (STRUKTUR ASLI) ================= --}}
<nav class="main-header navbar navbar-expand navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="fas fa-tachometer-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-link nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </li>
    </ul>
</nav>

{{-- ================= SIDEBAR (FUNGSI TETAP) ================= --}}
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}"
             class="brand-image img-circle elevation-3">
        <span class="brand-text">Pemilihan Motor</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                data-accordion="false">

                <li class="nav-header">DATA</li>

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- MOTOR --}}
                <li class="nav-item has-treeview {{ request()->routeIs('motor.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('motor.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-motorcycle"></i>
                        <p>
                            Data Motor
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('motor.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Motor</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('motor.topsis') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>TOPSIS</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- PEMBELI --}}
                <li class="nav-item has-treeview {{ request()->routeIs('pembeli.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('pembeli.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Data Pembeli
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('pembeli.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Pembeli</p>
                            </a>
                        </li>
                        @if($isAdmin)
                        <li class="nav-item">
                            <a href="{{ route('pembeli.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tambah Pembeli</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>

                {{-- PELUNASAN --}}
                <li class="nav-item has-treeview {{ request()->routeIs('pelunasan.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('pelunasan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Pelunasan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('pelunasan.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Pelunasan</p>
                            </a>
                        </li>
                        @if($isAdmin)
                        <li class="nav-item">
                            <a href="{{ route('pelunasan.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tambah Pelunasan</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>

{{-- ================= CONTENT ================= --}}
<div class="content-wrapper bg-dark">
    <section class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">@yield('title')</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @yield('content')
        </div>
    </section>
</div>

<footer class="main-footer footer-dark text-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <strong>&copy; 2025 Sistem Pemilihan Motor</strong>
        </div>
        <div class="footer-right">
            <span class="badge footer-badge">
                Kelompok 7
            </span>
        </div>
    </div>
</footer>

</div>

{{-- ================= JS (JANGAN DIUBAH) ================= --}}
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const btn = document.getElementById('btnHapusSemua');
    const form = document.getElementById('formHapusSemua');

    if (!btn || !form) {
        console.warn('Hapus semua: button atau form tidak ditemukan');
        return;
    }

    btn.addEventListener('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Hapus semua data?',
            text: 'Semua motor pada kategori ini akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus semua',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@yield('scripts')

</body>
</html>
