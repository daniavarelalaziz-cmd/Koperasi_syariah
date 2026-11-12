@extends('layouts.app')

@section('title', 'Pusat Laporan Koperasi')

@section('content')
<div class="mb-4">
    <h4 class="mb-0 fw-bold">Pusat Laporan & Rekapitulasi</h4>
    <small class="text-muted">Akses dan cetak laporan keuangan, simpanan, pembiayaan, dan angsuran</small>
</div>

<div class="row g-4">
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="rounded-circle bg-primary-subtle text-primary mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-cash-stack fs-3"></i>
            </div>
            <h5 class="fw-bold">Laporan Simpanan</h5>
            <p class="text-muted small">Rekapitulasi mutasi setoran dan penarikan kas simpanan anggota per periode.</p>
            <a href="{{ route('admin.laporan.simpanan') }}" class="btn btn-outline-primary mt-auto">Buka Laporan &rarr;</a>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="rounded-circle bg-success-subtle text-success mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-wallet2 fs-3"></i>
            </div>
            <h5 class="fw-bold">Laporan Pembiayaan</h5>
            <p class="text-muted small">Daftar kontrak pinjaman yang disalurkan, jangka waktu, dan statusnya.</p>
            <a href="{{ route('admin.laporan.pinjaman') }}" class="btn btn-outline-success mt-auto">Buka Laporan &rarr;</a>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="rounded-circle bg-warning-subtle text-warning mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-receipt-cutoff fs-3"></i>
            </div>
            <h5 class="fw-bold">Laporan Angsuran</h5>
            <p class="text-muted small">Rekap pembayaran angsuran bulanan yang diterima kasir (pokok & bagi hasil 2%).</p>
            <a href="{{ route('admin.laporan.angsuran') }}" class="btn btn-outline-warning mt-auto">Buka Laporan &rarr;</a>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="rounded-circle bg-info-subtle text-info mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-people fs-3"></i>
            </div>
            <h5 class="fw-bold">Rekapitulasi Anggota</h5>
            <p class="text-muted small">Daftar keanggotaan aktif beserta akumulasi saldo simpanan masing-masing.</p>
            <a href="{{ route('admin.laporan.anggota') }}" class="btn btn-outline-info mt-auto">Buka Laporan &rarr;</a>
        </div>
    </div>
</div>
@endsection

