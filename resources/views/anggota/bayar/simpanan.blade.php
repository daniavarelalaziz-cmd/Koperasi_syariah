@extends('layouts.app')

@section('title', 'Bayar Simpanan via QRIS')

@section('content')
<div class="container" style="max-width: 540px;">
    <div class="mb-3">
        <a href="{{ route('anggota.simpanan.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card border-0 shadow">
        <div class="card-header bg-primary text-white text-center py-3">
            <h5 class="mb-0"><i class="bi bi-qr-code me-2"></i>Bayar Simpanan via QRIS</h5>
        </div>
        <div class="card-body p-4">

            {{-- Info Pembayaran --}}
            <div class="bg-light rounded p-3 mb-4">
                <div class="row g-2">
                    <div class="col-6 text-muted small">Jenis Simpanan</div>
                    <div class="col-6 fw-semibold small">{{ $jenisSimpanan->nama_simpanan }}</div>

                    <div class="col-6 text-muted small">Nama Anggota</div>
                    <div class="col-6 fw-semibold small">{{ $anggota->nama_anggota ?? auth()->user()->username }}</div>

                    <div class="col-12"><hr class="my-1"></div>

                    <div class="col-6 text-muted">Nominal</div>
                    <div class="col-6">
                        @if($nominal > 0)
                            <span class="fs-5 fw-bold text-primary">Rp {{ number_format($nominal, 0, ',', '.') }}</span>
                        @else
                            <span class="text-muted fst-italic">Sukarela (bebas)</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($qrsvg)
                {{-- QR Code --}}
                <div class="text-center mb-4">
                    <div class="border rounded p-3 d-inline-block bg-white">
                        {!! $qrsvg !!}
                    </div>
                    @if($nominal == 0)
                        <div class="alert alert-info mt-3 py-2 text-start">
                            <i class="bi bi-info-circle me-1"></i>
                            <small>Simpanan sukarela: masukkan nominal sesuai keinginan Anda saat scan QR.</small>
                        </div>
                    @endif
                    <p class="text-muted small mt-2 mb-0">
                        <i class="bi bi-phone me-1"></i>Scan dengan aplikasi m-banking atau e-wallet
                    </p>
                </div>

                {{-- Keterangan --}}
                <div class="alert alert-warning d-flex align-items-start gap-2 mb-4">
                    <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
                    <div class="small">
                        <strong>Penting:</strong> Setelah pembayaran berhasil, tunjukkan bukti transfer
                        kepada petugas koperasi. Petugas akan mencatat simpanan Anda.
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button onclick="window.print()" class="btn btn-outline-secondary d-print-none">
                        <i class="bi bi-printer me-2"></i>Cetak / Screenshot QR
                    </button>
                </div>

            @else
                <div class="text-center py-4">
                    <i class="bi bi-qr-code display-1 text-muted"></i>
                    <h6 class="mt-3 text-muted">Pembayaran QRIS Belum Tersedia</h6>
                    <p class="text-muted small">
                        Admin belum mengkonfigurasi QRIS string. Silakan hubungi petugas koperasi
                        untuk pembayaran langsung.
                    </p>
                    <a href="{{ route('anggota.simpanan.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            @endif

        </div>

        @if($qrsvg)
        <div class="card-footer text-center bg-light py-2">
            <small class="text-muted">
                <i class="bi bi-shield-check me-1 text-success"></i>
                Didukung semua bank & e-wallet (GoPay, OVO, Dana, ShopeePay, dll)
            </small>
        </div>
        @endif
    </div>
</div>
@endsection

