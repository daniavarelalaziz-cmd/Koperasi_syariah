<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Koperasi Syariah')</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --navbar-height: 56px;
            --sidebar-bg: #1a3c5e;
            --sidebar-active: #2563a8;
            --sidebar-hover: rgba(255,255,255,0.1);
            --sidebar-text: rgba(255,255,255,0.85);
            --sidebar-muted: rgba(255,255,255,0.5);
        }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: #f4f6fb;
        }
        /* ── NAVBAR ──────────────────────────────────────── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--navbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            z-index: 100;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
        }
        .topbar .page-title {
            font-weight: 600;
            font-size: 1rem;
            color: #374151;
            flex: 1;
        }
        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        /* ── SIDEBAR ─────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            overflow-y: auto;
            z-index: 200;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand .brand-name {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
        }
        .sidebar-brand .brand-sub {
            font-size: 0.72rem;
            color: var(--sidebar-muted);
        }
        .sidebar-nav {
            flex: 1;
            padding: .75rem 0;
        }
        .nav-section-title {
            padding: .5rem 1.25rem .25rem;
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--sidebar-muted);
        }
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .55rem 1.25rem;
            color: var(--sidebar-text);
            font-size: .875rem;
            border-radius: 0;
            transition: background .15s;
        }
        .sidebar-nav .nav-link i {
            font-size: 1rem;
            width: 1.25rem;
            text-align: center;
            flex-shrink: 0;
        }
        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }
        .sidebar-nav .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
            font-weight: 600;
        }
        .sidebar-footer {
            padding: .75rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: .75rem;
            color: var(--sidebar-muted);
        }
        /* ── MAIN CONTENT ────────────────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            min-height: calc(100vh - var(--navbar-height));
            padding: 1.75rem;
        }
        /* ── STAT CARD ───────────────────────────────────── */
        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.07);
        }
        .stat-card .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.1;
            color: #1e293b;
        }
        .stat-card .stat-label {
            font-size: .8rem;
            color: #64748b;
            margin-top: .15rem;
        }
        /* ── TABLE ───────────────────────────────────────── */
        .table-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.07);
            overflow: hidden;
        }
        .table-card .table {
            margin-bottom: 0;
        }
        .table-card .table th {
            background: #f8fafc;
            font-size: .8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
            padding: .75rem 1rem;
        }
        .table-card .table td {
            padding: .75rem 1rem;
            vertical-align: middle;
            font-size: .875rem;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-card .table tbody tr:last-child td {
            border-bottom: none;
        }
        .table-card .table tbody tr:hover {
            background: #f8fafc;
        }
        /* ── PRINT ───────────────────────────────────────── */
        @media print {
            .sidebar, .topbar, .no-print { display: none !important; }
            .main-wrapper { margin: 0; padding: 0; }
        }
        /* ── SCROLLBAR ───────────────────────────────────── */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }
    </style>
    @stack('styles')
</head>
<body>

<!-- ─── SIDEBAR ─────────────────────────────────────────────── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#2563a8,#38bdf8);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-bank2 text-white" style="font-size:1.1rem;"></i>
            </div>
            <div>
                <div class="brand-name">Koperasi Syariah</div>
                <div class="brand-sub">Sistem Informasi</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @php $role = auth()->user()->role ?? 'anggota'; @endphp

        {{-- ═══════════════ ADMIN ═══════════════ --}}
        @if($role === 'admin')
            <div class="nav-section-title">Utama</div>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nav-section-title">Keanggotaan</div>
            <a href="{{ route('admin.anggota.index') }}"
               class="nav-link {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Data Anggota
            </a>

            <div class="nav-section-title">Keuangan</div>
            <a href="{{ route('admin.simpanan.index') }}"
               class="nav-link {{ request()->routeIs('admin.simpanan.*') ? 'active' : '' }}">
                <i class="bi bi-piggy-bank-fill"></i> Simpanan
            </a>
            <a href="{{ route('admin.pengajuan.index') }}"
               class="nav-link {{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i> Pengajuan Pinjaman
            </a>
            <a href="{{ route('admin.pinjaman.index') }}"
               class="nav-link {{ request()->routeIs('admin.pinjaman.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-2-front-fill"></i> Pinjaman & Angsuran
            </a>

            <div class="nav-section-title">Lainnya</div>
            <a href="{{ route('admin.laporan.index') }}"
               class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i> Laporan
            </a>
            <a href="{{ route('admin.pengaturan.index') }}"
               class="nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i> Pengaturan
            </a>

        {{-- ═══════════════ PIMPINAN ═══════════════ --}}
        @elseif($role === 'pimpinan')
            <div class="nav-section-title">Utama</div>
            <a href="{{ route('pimpinan.dashboard') }}"
               class="nav-link {{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <div class="nav-section-title">Laporan</div>
            <a href="{{ route('pimpinan.laporan.index') }}"
               class="nav-link {{ request()->routeIs('pimpinan.laporan.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i> Laporan Keuangan
            </a>

        {{-- ═══════════════ ANGGOTA ═══════════════ --}}
        @elseif($role === 'anggota')
            <div class="nav-section-title">Utama</div>
            <a href="{{ route('anggota.dashboard') }}"
               class="nav-link {{ request()->routeIs('anggota.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i> Dashboard
            </a>
            <div class="nav-section-title">Keuangan Saya</div>
            <a href="{{ route('anggota.simpanan.index') }}"
               class="nav-link {{ request()->routeIs('anggota.simpanan.*') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i> Simpanan Saya
            </a>
            <a href="{{ route('anggota.pengajuan.index') }}"
               class="nav-link {{ request()->routeIs('anggota.pengajuan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-plus-fill"></i> Pengajuan Pinjaman
            </a>
            <a href="{{ route('anggota.angsuran.index') }}"
               class="nav-link {{ request()->routeIs('anggota.angsuran.*') ? 'active' : '' }}">
                <i class="bi bi-calendar2-check-fill"></i> Angsuran Saya
            </a>
            <div class="nav-section-title">Pembayaran</div>
            <a href="{{ route('anggota.simpanan.index') }}"
               class="nav-link {{ request()->routeIs('anggota.bayar.simpanan') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i> Bayar QRIS
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div>{{ config('app.name', 'Koperasi Syariah') }}</div>
        <div>v1.0 &copy; {{ date('Y') }}</div>
    </div>
</aside>

<!-- ─── TOPBAR ──────────────────────────────────────────────── -->
<header class="topbar">
    <span class="page-title">@yield('page-title', 'Dashboard')</span>
    <div class="user-info">
        <i class="bi bi-bell text-secondary fs-5"></i>
        <div class="vr mx-1"></div>
        <div class="d-flex align-items-center gap-2">
            <div style="width:32px;height:32px;background:#e2e8f0;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-person-fill text-secondary"></i>
            </div>
            <div class="d-none d-sm-block">
                <div class="fw-semibold" style="font-size:.85rem;line-height:1.2;">{{ auth()->user()->username }}</div>
                @php
                    $badgeColor = match(auth()->user()->role) {
                        'admin'    => 'primary',
                        'pimpinan' => 'success',
                        'anggota'  => 'info',
                        default    => 'secondary',
                    };
                @endphp
                <span class="badge bg-{{ $badgeColor }}" style="font-size:.65rem;">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="ms-1">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Logout</span>
            </button>
        </form>
    </div>
</header>

<!-- ─── MAIN CONTENT ────────────────────────────────────────── -->
<main class="main-wrapper">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
            <div>{{ session('warning') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
