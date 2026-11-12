@extends('layouts.app')

@section('title', 'Dashboard Pimpinan — Koperasi Syariah')
@section('page-title', 'Dashboard Pimpinan')

@section('content')
<div class="row g-3 mb-4">

    {{-- Card: Total Anggota --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card bg-white">
            <div class="stat-icon" style="background:#eff6ff;">
                <i class="bi bi-people-fill" style="color:#2563a8;"></i>
            </div>
            <div>
                <div class="stat-value">{{ $totalAnggota ?? 0 }}</div>
                <div class="stat-label">Total Anggota</div>
            </div>
        </div>
    </div>

    {{-- Card: Total Dana Simpanan --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card bg-white">
            <div class="stat-icon" style="background:#fefce8;">
                <i class="bi bi-piggy-bank-fill" style="color:#ca8a04;"></i>
            </div>
            <div>
                <div class="stat-value" style="font-size:1.05rem;">
                    Rp {{ number_format($totalSimpanan ?? 0, 0, ',', '.') }}
                </div>
                <div class="stat-label">Total Dana Simpanan</div>
            </div>
        </div>
    </div>

    {{-- Card: Total Pinjaman Berjalan --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card bg-white">
            <div class="stat-icon" style="background:#fff7ed;">
                <i class="bi bi-credit-card-2-front-fill" style="color:#ea580c;"></i>
            </div>
            <div>
                <div class="stat-value" style="font-size:1.05rem;">
                    Rp {{ number_format($totalPinjaman ?? 0, 0, ',', '.') }}
                </div>
                <div class="stat-label">Sisa Pokok Pinjaman</div>
            </div>
        </div>
    </div>

    {{-- Card: Pinjaman Aktif --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card bg-white">
            <div class="stat-icon" style="background:#fdf4ff;">
                <i class="bi bi-graph-up-arrow" style="color:#9333ea;"></i>
            </div>
            <div>
                <div class="stat-value">{{ $pinjamanBerjalan ?? 0 }}</div>
                <div class="stat-label">Pinjaman Berjalan</div>
            </div>
        </div>
    </div>

</div>

<div class="row g-3">
    <div class="col-12">
        <div class="bg-white rounded-3 p-4 shadow-sm">
            <h6 class="fw-semibold mb-3 text-muted"><i class="bi bi-bar-chart-fill text-success me-1"></i> Laporan</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('pimpinan.laporan.index') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-file-earmark-bar-graph-fill me-1"></i> Lihat Laporan Keuangan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
