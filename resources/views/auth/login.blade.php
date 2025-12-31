<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Pemilihan Motor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ADMINLTE CORE --}}
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <style>
        /* ===================================================
           LOGIN – DARK MODERN THEME
           =================================================== */

        body.login-page {
            background: radial-gradient(circle at top, #0f172a, #020617);
            color: #e5e7eb;
        }

        /* Login box animation */
        .login-box {
            animation: fadeSlideUp .6s ease forwards;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px) scale(.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Logo */
        .login-logo a {
            color: #e5e7eb;
            font-weight: 600;
            letter-spacing: .04em;
        }

        .login-logo span {
            color: #60a5fa;
        }

        /* Card */
        .login-card-body {
            background: #0b1220;
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(0,0,0,.45);
        }

        .card {
            background: transparent;
            border: none;
        }

        /* Text */
        .login-box-msg {
            color: #94a3b8;
            font-size: .85rem;
        }

        /* Input */
        .form-control {
            background: #020617;
            border: 1px solid rgba(255,255,255,.12);
            color: #e5e7eb;
            border-radius: .6rem;
        }

        .form-control:focus {
            background: #020617;
            border-color: #3b82f6;
            box-shadow: 0 0 0 .15rem rgba(59,130,246,.25);
            color: #fff;
        }

        .form-control::placeholder {
            color: #64748b;
        }

        /* Input group icon */
        .input-group-text {
            background: #020617;
            border: 1px solid rgba(255,255,255,.12);
            color: #94a3b8;
            cursor: pointer;
        }

        /* Checkbox */
        .icheck-primary label {
            color: #94a3b8;
            font-size: .8rem;
        }

        /* Button */
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border: none;
            border-radius: .6rem;
            font-size: .85rem;
            transition: all .2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(59,130,246,.35);
        }

        /* Error */
        .alert-danger {
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.35);
            color: #fecaca;
            font-size: .8rem;
        }

        /* Footer hint */
        .login-footer {
            text-align: center;
            margin-top: .75rem;
            font-size: .7rem;
            color: #64748b;
        }
    </style>
</head>

<body class="hold-transition login-page">

<div class="login-box">

    {{-- LOGO --}}
    <div class="login-logo">
        <a href="#">
            <span>Sistem</span> Pemilihan Motor
        </a>
    </div>

    {{-- CARD --}}
    <div class="card">
        <div class="card-body login-card-body">

            <p class="login-box-msg">
                Silakan login untuk melanjutkan
            </p>

            {{-- ERROR LOGIN --}}
            @if ($errors->has('login'))
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $errors->first('login') }}
                </div>
            @endif

            <form action="{{ route('login_proses') }}" method="post">
                @csrf

                {{-- USERNAME --}}
                <div class="input-group mb-3">
                    <input type="text"
                           name="username"
                           class="form-control @error('username') is-invalid @enderror"
                           placeholder="Username"
                           value="{{ old('username') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                @error('username')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                {{-- PASSWORD --}}
                <div class="input-group mb-3 mt-2">
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text" id="togglePassword">
                            <span class="fas fa-eye" id="eyeIcon"></span>
                        </div>
                    </div>
                </div>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                {{-- REMEMBER --}}
                <div class="row mt-3 align-items-center">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">Ingat saya</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </button>
                    </div>
                </div>
            </form>

            <div class="login-footer">
                Sistem Pendukung Keputusan • TOPSIS
            </div>
        </div>
    </div>
</div>

{{-- JS --}}
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

{{-- TOGGLE PASSWORD --}}
<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const pass = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');

    if (pass.type === 'password') {
        pass.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        pass.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
});
</script>

{{-- SWEETALERT ERROR --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if ($errors->has('login'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Login Gagal',
    text: '{{ $errors->first('login') }}',
    background: '#020617',
    color: '#e5e7eb',
    confirmButtonColor: '#2563eb'
});
</script>
@endif

</body>
</html>
