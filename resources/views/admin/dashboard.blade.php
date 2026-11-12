@extends('layouts.app')

@section('title', 'Dashboard Admin — Koperasi Syariah')
@section('page-title', 'Dashboard Admin')

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

    {{-- Card: Anggota Aktif --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card bg-white">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="bi bi-person-check-fill" style="color:#16a34a;"></i>
            </div>
            <div>
                <div class="stat-value">{{ $anggotaAktif ?? 0 }}</div>
                <div class="stat-label">Anggota Aktif</div>
            </div>
        </div>
    </div>

    {{-- Card: Total Simpanan --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card bg-white">
            <div class="stat-icon" style="background:#fefce8;">
                <i class="bi bi-piggy-bank-fill" style="color:#ca8a04;"></i>
            </div>
            <div>
                <div class="stat-value" style="font-size:1.1rem;">
                    Rp {{ number_format($totalSimpanan ?? 0, 0, ',', '.') }}
                </div>
                <div class="stat-label">Total Dana Simpanan</div>
            </div>
        </div>
    </div>

    {{-- Card: Pengajuan Pending --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card bg-white">
            <div class="stat-icon" style="background:#fdf4ff;">
                <i class="bi bi-hourglass-split" style="color:#9333ea;"></i>
            </div>
            <div>
                <div class="stat-value">{{ $pengajuanPending ?? 0 }}</div>
                <div class="stat-label">Pengajuan Menunggu</div>
            </div>
        </div>
    </div>

</div>

{{-- Quick Links --}}
<div class="row g-3">
    <div class="col-12">
        <div class="bg-white rounded-3 p-4 shadow-sm">
            <h6 class="fw-semibold mb-3 text-muted"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Aksi Cepat</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambah Anggota
                </a>
                <a href="{{ route('admin.simpanan.create') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle-fill me-1"></i> Catat Simpanan
                </a>
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-people me-1"></i> Lihat Semua Anggota
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
