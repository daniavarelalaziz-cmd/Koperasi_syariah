@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@section('content')
<div class="mb-4">
    <h4 class="mb-0 fw-bold">Assalamu'alaikum, {{ $anggota ? $anggota->nama_lengkap : $user->username }}!</h4>
    <small class="text-muted">Selamat datang di Portal Layanan Mandiri Koperasi Syariah Punggur Inti Sejahtera</small>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center bg-white">
            <small class="text-muted text-uppercase fw-semibold">Total Saldo Simpanan</small>
            <h3 class="fw-bold text-success mt-2 mb-0">Rp {{ number_format($saldoTotal, 0, ',', '.') }}</h3>
            <small class="text-muted"><a href="{{ route('anggota.simpanan.index') }}" class="text-decoration-none">Lihat Buku Tabungan &rarr;</a></small>
        </div>
    </div>
    @foreach($saldoPerJenis as $info)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 text-center bg-white">
                <small class="text-muted text-uppercase fw-semibold">{{ $info['nama'] }}</small>
                <h4 class="fw-bold text-primary mt-2 mb-0">Rp {{ number_format($info['saldo'], 0, ',', '.') }}</h4>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-wallet2 me-2 text-primary"></i>Status Pembiayaan / Pinjaman Berjalan</h6>
                <a href="{{ route('anggota.angsuran.index') }}" class="btn btn-sm btn-outline-primary">Lihat Jadwal Angsuran</a>
            </div>
            <div class="card-body p-4 pt-0">
                @if($pinjamanAktif)
                    <div class="p-3 bg-light rounded mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Nomor Kontrak:</span>
                            <span class="fw-bold">{{ $pinjamanAktif->no_pinjaman }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pokok Pembiayaan:</span>
                            <span class="fw-semibold">Rp {{ number_format($pinjamanAktif->jumlah_pokok, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Angsuran / Bulan:</span>
                            <span class="fw-bold text-primary">Rp {{ number_format($pinjamanAktif->total_angsuran_per_bulan, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Progres Angsuran:</span>
                            <span class="badge bg-success">{{ $pinjamanAktif->angsuran->count() }} dari {{ $pinjamanAktif->lama_bulan }} Bulan Terbayar</span>
                        </div>
                        <div class="progress mt-2" style="height: 12px;">
                            @php
                                $pct = ($pinjamanAktif->angsuran->count() / $pinjamanAktif->lama_bulan) * 100;
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check2-circle fs-1 text-success mb-2 d-block"></i>
                        Anda saat ini tidak memiliki pinjaman berjalan.
                        <div class="mt-3">
                            <a href="{{ route('anggota.pengajuan.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Ajukan Pembiayaan Baru
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-vcard me-2 text-primary"></i>Kartu Tanda Anggota</h6>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="p-3 bg-primary text-white rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-white-50">KOPERASI SYARIAH PUNGGUR</small>
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $anggota ? $anggota->nama_lengkap : $user->username }}</h5>
                    <div class="font-monospace fs-6 text-white-50 mb-3">{{ $anggota ? $anggota->no_anggota : '-' }}</div>
                    <div class="d-flex justify-content-between small text-white-50">
                        <span>NIK: {{ $anggota ? $anggota->nik : '-' }}</span>
                        <span>Sejak: {{ ($anggota && $anggota->tgl_gabung) ? $anggota->tgl_gabung->format('M Y') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

