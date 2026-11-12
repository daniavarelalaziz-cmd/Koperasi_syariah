@extends('layouts.app')

@section('title', 'Bukti Transaksi Simpanan - ' . $trx->no_transaksi)

@section('content')
<div class="mb-4 d-print-none">
    <a href="{{ route('admin.simpanan.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Simpanan
    </a>
    <div class="d-flex justify-content-between align-items-center mt-2">
        <h4 class="mb-0 fw-bold">Bukti Transaksi Simpanan</h4>
        <div>
            <button onclick="window.print()" class="btn btn-outline-primary"><i class="bi bi-printer me-1"></i> Cetak Struk</button>
            <a href="{{ route('admin.simpanan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Transaksi Baru</a>
        </div>
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
                    BUKTI {{ $trx->jenis_transaksi === 'setor' ? 'PENERIMAAN SETORAN' : 'PENARIKAN SIMPANAN' }}
                </h6>
                <small class="text-muted">No: <strong>{{ $trx->no_transaksi }}</strong></small>
            </div>

            <table class="table table-sm table-borderless mb-4">
                <tr>
                    <td style="width: 35%;" class="text-muted">Tanggal Transaksi</td>
                    <td style="width: 5%;">:</td>
                    <td class="fw-semibold">{{ $trx->tgl_transaksi->format('d/m/Y H:i:s') }}</td>
                </tr>
                <tr>
                    <td class="text-muted">No. Anggota</td>
                    <td>:</td>
                    <td class="fw-semibold">{{ $trx->anggota->no_anggota }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Nama Anggota</td>
                    <td>:</td>
                    <td class="fw-semibold">{{ $trx->anggota->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Jenis Simpanan</td>
                    <td>:</td>
                    <td class="fw-semibold">{{ $trx->jenisSimpanan->nama_simpanan }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Tipe Transaksi</td>
                    <td>:</td>
                    <td>
                        <span class="badge {{ $trx->jenis_transaksi === 'setor' ? 'bg-success' : 'bg-danger' }}">
                            {{ strtoupper($trx->jenis_transaksi) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Jumlah Nominal</td>
                    <td>:</td>
                    <td class="fs-5 fw-bold text-primary">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Keterangan</td>
                    <td>:</td>
                    <td>{{ $trx->keterangan ?: '-' }}</td>
                </tr>
            </table>

            <div class="row text-center mt-4 pt-3 border-top">
                <div class="col-6">
                    <small class="text-muted d-block mb-4">Anggota</small>
                    <div class="fw-semibold mt-4">({{ $trx->anggota->nama_lengkap }})</div>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block mb-4">Petugas Kasir</small>
                    <div class="fw-semibold mt-4">({{ $trx->petugas ? $trx->petugas->username : 'Admin' }})</div>
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

