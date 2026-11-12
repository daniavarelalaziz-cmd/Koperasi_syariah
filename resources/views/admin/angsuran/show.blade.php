@extends('layouts.app')

@section('title', 'Kwitansi Angsuran - ' . $angsuran->no_bukti_angsuran)

@section('content')
<div class="mb-4 d-print-none">
    <a href="{{ route('admin.pinjaman.show', $angsuran->pinjaman_id) }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Pinjaman
    </a>
    <div class="d-flex justify-content-between align-items-center mt-2">
        <h4 class="mb-0 fw-bold">Bukti Pembayaran Angsuran</h4>
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer me-1"></i> Cetak Kwitansi</button>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm p-4 print-area bg-white">
            <div class="text-center border-bottom pb-3 mb-3">
                <h5 class="fw-bold mb-0">KOPERASI SYARIAH PUNGGUR INTI SEJAHTERA</h5>
                <small class="text-muted">Desa Sidomulyo, Kec. Punggur, Kab. Lampung Tengah</small><br>
                <small class="text-muted">Badan Hukum: 002117/BH/M.KUKM.2/IX/2016</small>
            </div>

            <div class="text-center mb-4">
                <h6 class="fw-bold text-uppercase text-decoration-underline mb-1">
                    BUKTI PEMBAYARAN ANGSURAN PEMBIAYAAN
                </h6>
                <small class="text-muted">No. Kwitansi: <strong>{{ $angsuran->no_bukti_angsuran }}</strong></small>
            </div>

            <table class="table table-sm table-borderless mb-4">
                <tr>
                    <td style="width: 38%;" class="text-muted">Tanggal Pembayaran</td>
                    <td style="width: 4%;">:</td>
                    <td class="fw-semibold">{{ $angsuran->tgl_bayar->format('d/m/Y H:i:s') }}</td>
                </tr>
                <tr>
                    <td class="text-muted">No. Pinjaman</td>
                    <td>:</td>
                    <td class="fw-semibold">{{ $angsuran->pinjaman->no_pinjaman }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Nama Anggota</td>
                    <td>:</td>
                    <td class="fw-semibold">{{ $angsuran->pinjaman->anggota->nama_lengkap }} ({{ $angsuran->pinjaman->anggota->no_anggota }})</td>
                </tr>
                <tr>
                    <td class="text-muted">Angsuran Ke</td>
                    <td>:</td>
                    <td class="fw-bold text-primary">Ke-{{ $angsuran->angsuran_ke }} dari {{ $angsuran->pinjaman->lama_bulan }} Bulan</td>
                </tr>
                <tr>
                    <td class="text-muted">Pembayaran Pokok</td>
                    <td>:</td>
                    <td>Rp {{ number_format($angsuran->bayar_pokok, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Bagi Hasil Syariah (2%)</td>
                    <td>:</td>
                    <td>Rp {{ number_format($angsuran->bayar_bagi_hasil, 0, ',', '.') }}</td>
                </tr>
                <tr class="border-top">
                    <td class="fw-bold pt-2 text-dark">Total Yang Dibayar</td>
                    <td class="fw-bold pt-2">:</td>
                    <td class="fs-5 fw-bold text-success pt-2">Rp {{ number_format($angsuran->total_bayar, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Keterangan</td>
                    <td>:</td>
                    <td>{{ $angsuran->keterangan ?: 'Pembayaran angsuran syariah' }}</td>
                </tr>
            </table>

            <div class="row text-center mt-4 pt-3 border-top">
                <div class="col-6">
                    <small class="text-muted d-block mb-4">Anggota Penyetor</small>
                    <div class="fw-semibold mt-4">({{ $angsuran->pinjaman->anggota->nama_lengkap }})</div>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block mb-4">Petugas Kasir</small>
                    <div class="fw-semibold mt-4">({{ $angsuran->petugas ? $angsuran->petugas->username : 'Admin' }})</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .print-area, .print-area * {
        visibility: visible;
    }
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection

