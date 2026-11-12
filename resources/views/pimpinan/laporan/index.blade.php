@extends('layouts.app')

@section('title', 'Laporan Monitoring Koperasi')

@section('content')
<div class="mb-4">
    <h4 class="mb-0 fw-bold">Pusat Laporan & Monitoring Eksekutif</h4>
    <small class="text-muted">Akses laporan keuangan, perkembangan anggota, dan pembiayaan koperasi syariah</small>
</div>

<div class="row g-4">
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="rounded-circle bg-primary-subtle text-primary mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-cash-stack fs-3"></i>
            </div>
            <h5 class="fw-bold">Laporan Simpanan</h5>
            <p class="text-muted small">Rekapitulasi seluruh mutasi simpanan pokok, wajib, dan sukarela anggota.</p>
            <a href="{{ route('pimpinan.laporan.simpanan') }}" class="btn btn-outline-primary mt-auto">Buka Laporan &rarr;</a>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="rounded-circle bg-success-subtle text-success mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-wallet2 fs-3"></i>
            </div>
            <h5 class="fw-bold">Laporan Pembiayaan</h5>
            <p class="text-muted small">Daftar pinjaman yang disalurkan, jangka waktu, dan progres penyelesaiannya.</p>
            <a href="{{ route('pimpinan.laporan.pinjaman') }}" class="btn btn-outline-success mt-auto">Buka Laporan &rarr;</a>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="rounded-circle bg-warning-subtle text-warning mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-receipt-cutoff fs-3"></i>
            </div>
            <h5 class="fw-bold">Laporan Angsuran</h5>
            <p class="text-muted small">Penerimaan cicilan bulanan beserta rincian pokok dan porsi bagi hasil 2%.</p>
            <a href="{{ route('pimpinan.laporan.angsuran') }}" class="btn btn-outline-warning mt-auto">Buka Laporan &rarr;</a>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="rounded-circle bg-info-subtle text-info mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-people fs-3"></i>
            </div>
            <h5 class="fw-bold">Rekapitulasi Anggota</h5>
            <p class="text-muted small">Daftar keanggotaan aktif serta total saldo simpanan masing-masing di koperasi.</p>
            <a href="{{ route('pimpinan.laporan.anggota') }}" class="btn btn-outline-info mt-auto">Buka Laporan &rarr;</a>
        </div>
    </div>
</div>
@endsection

