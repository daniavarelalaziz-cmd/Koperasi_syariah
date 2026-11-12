<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Koperasi Syariah</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a3c5e 0%, #2563a8 60%, #38bdf8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 1rem;
        }
        .login-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.75rem;
        }
        .login-logo .logo-icon {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #fff;
            margin-bottom: .75rem;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .login-logo .logo-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #fff;
        }
        .login-logo .logo-sub {
            font-size: .8rem;
            color: rgba(255,255,255,0.75);
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            padding: 2rem;
        }
        .login-card .card-header-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: .25rem;
        }
        .login-card .card-subtext {
            font-size: .82rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }
        .form-label {
            font-size: .82rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: .35rem;
        }
        .form-control {
            border-radius: 8px;
            font-size: .9rem;
            padding: .6rem .9rem;
            border: 1.5px solid #e2e8f0;
            transition: border-color .2s;
        }
        .form-control:focus {
            border-color: #2563a8;
            box-shadow: 0 0 0 3px rgba(37,99,168,0.12);
        }
        .input-group-text {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            color: #94a3b8;
        }
        .btn-login {
            background: linear-gradient(135deg, #1a3c5e, #2563a8);
            border: none;
            border-radius: 8px;
            padding: .65rem;
            font-weight: 600;
            font-size: .9rem;
            letter-spacing: .02em;
            transition: opacity .2s;
        }
        .btn-login:hover { opacity: .9; }
        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: .75rem;
            color: rgba(255,255,255,0.65);
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <!-- Logo -->
    <div class="login-logo">
        <div class="logo-icon">
            <i class="bi bi-bank2"></i>
        </div>
        <div class="logo-title">Koperasi Syariah</div>
        <div class="logo-sub">Sistem Informasi Koperasi</div>
    </div>

    <!-- Card Login -->
    <div class="login-card">
        <div class="card-header-text">Selamat Datang 👋</div>
        <div class="card-subtext">Masuk ke akun Anda untuk melanjutkan</div>

        {{-- Flash Error --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        class="form-control @error('username') is-invalid @enderror"
                        placeholder="Masukkan username"
                        autocomplete="username"
                        autofocus
                        required
                    >
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-login text-white">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </button>
            </div>
        </form>
    </div>

    <div class="footer-text">
        &copy; {{ date('Y') }} Koperasi Syariah. All rights reserved.
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    toggleBtn.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });
</script>
</body>
</html>
