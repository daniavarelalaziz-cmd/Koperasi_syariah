@extends('layouts.app')

@section('title', 'Bayar Angsuran via QRIS')

@section('content')
<div class="container" style="max-width: 540px;">
    <div class="mb-3">
        <a href="{{ route('anggota.angsuran.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card border-0 shadow">
        <div class="card-header bg-success text-white text-center py-3">
            <h5 class="mb-0"><i class="bi bi-qr-code me-2"></i>Bayar via QRIS</h5>
        </div>
        <div class="card-body p-4">

            {{-- Info Pembayaran --}}
            <div class="bg-light rounded p-3 mb-4">
                <div class="row g-2">
                    <div class="col-6 text-muted small">No. Pinjaman</div>
                    <div class="col-6 fw-semibold small">{{ $pinjaman->no_pinjaman }}</div>

                    <div class="col-6 text-muted small">Angsuran Ke</div>
                    <div class="col-6 fw-semibold small">{{ $angsuranKe }} / {{ $pinjaman->lama_bulan }}</div>

                    <div class="col-6 text-muted small">Jatuh Tempo</div>
                    <div class="col-6 fw-semibold small">{{ $tglJatuhTempo->format('d/m/Y') }}</div>

                    <div class="col-12"><hr class="my-1"></div>

                    <div class="col-6 text-muted">Total Bayar</div>
                    <div class="col-6">
                        <span class="fs-5 fw-bold text-success">Rp {{ number_format($nominal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($qrsvg)
                {{-- QR Code --}}
                <div class="text-center mb-4">
                    <div class="border rounded p-3 d-inline-block bg-white">
                        {!! $qrsvg !!}
                    </div>
                    <p class="text-muted small mt-2 mb-0">
                        <i class="bi bi-phone me-1"></i>Scan dengan aplikasi m-banking atau e-wallet
                    </p>
                </div>

                {{-- Keterangan --}}
                <div class="alert alert-warning d-flex align-items-start gap-2 mb-4">
                    <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
                    <div class="small">
                        <strong>Penting:</strong> Pastikan nominal pembayaran sesuai yaitu
                        <strong>Rp {{ number_format($nominal, 0, ',', '.') }}</strong>.
                        Setelah pembayaran berhasil, tunjukkan bukti transfer kepada petugas koperasi.
                        Petugas akan memverifikasi dan mencatat angsuran Anda.
                    </div>
                </div>

                {{-- Tombol cetak --}}
                <div class="d-grid gap-2">
                    <button onclick="window.print()" class="btn btn-outline-secondary d-print-none">
                        <i class="bi bi-printer me-2"></i>Cetak / Screenshot QR
                    </button>
                </div>

            @else
                {{-- QRIS belum dikonfigurasi --}}
                <div class="text-center py-4">
                    <i class="bi bi-qr-code display-1 text-muted"></i>
                    <h6 class="mt-3 text-muted">Pembayaran QRIS Belum Tersedia</h6>
                    <p class="text-muted small">
                        Admin belum mengkonfigurasi QRIS string. Silakan hubungi petugas koperasi
                        untuk pembayaran langsung.
                    </p>
                    <a href="{{ route('anggota.angsuran.index') }}" class="btn btn-primary">
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

