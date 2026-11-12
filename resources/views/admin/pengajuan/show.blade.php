@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan - ' . $pengajuan->no_pengajuan)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.pengajuan.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pengajuan
    </a>
    <h4 class="mb-0 fw-bold mt-2">Detail & Verifikasi Pengajuan Pembiayaan</h4>
    <small class="text-muted">Nomor Permohonan: <strong>{{ $pengajuan->no_pengajuan }}</strong></small>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Rincian Permohonan</h6>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Nama Pemohon</small>
                        <h6 class="fw-bold mb-0">{{ $pengajuan->anggota->nama_lengkap }}</h6>
                        <small class="text-muted">{{ $pengajuan->anggota->no_anggota }}</small>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Pekerjaan & Penghasilan</small>
                        <span class="fw-semibold">{{ $pengajuan->anggota->pekerjaan }}</span>
                        <small class="text-muted d-block">Rp {{ number_format($pengajuan->anggota->penghasilan_per_bulan, 0, ',', '.') }}/bln</small>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Jumlah Pinjaman Diminta</small>
                        <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($pengajuan->jumlah_pengajuan, 0, ',', '.') }}</h4>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Jangka Waktu (Tenor)</small>
                        <h5 class="fw-bold mb-0">{{ $pengajuan->jangka_waktu_bulan }} Bulan</h5>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Tujuan Pembiayaan</small>
                        <p class="mb-0">{{ $pengajuan->tujuan_pinjaman }}</p>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Total Saldo Simpanan Pemohon</small>
                        <span class="fw-bold text-success fs-5">Rp {{ number_format($pengajuan->anggota->saldo_total, 0, ',', '.') }}</span>
                        <small class="text-muted d-block">*Sesuai SOP, saldo simpanan dicek kasir sebelum persetujuan.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-paperclip me-2 text-primary"></i>Berkas Persyaratan</h6>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded text-center">
                            <i class="bi bi-person-badge fs-2 text-primary mb-2 d-block"></i>
                            <div class="fw-semibold mb-2">Foto KTP Suami / Istri</div>
                            @if($pengajuan->berkas_ktp)
                                <a href="{{ asset('storage/' . $pengajuan->berkas_ktp) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> Buka Berkas KTP
                                </a>
                            @else
                                <span class="badge bg-secondary">Tidak ada berkas terlampir</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded text-center">
                            <i class="bi bi-people fs-2 text-primary mb-2 d-block"></i>
                            <div class="fw-semibold mb-2">Kartu Keluarga (KK)</div>
                            @if($pengajuan->berkas_kk)
                                <a href="{{ asset('storage/' . $pengajuan->berkas_kk) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> Buka Berkas KK
                                </a>
                            @else
                                <span class="badge bg-secondary">Tidak ada berkas terlampir</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <!-- SIMULASI AKAD BAGI HASIL 2% -->
        @php
            $pokok = $pengajuan->jumlah_pengajuan;
            $tenor = $pengajuan->jangka_waktu_bulan;
            $bagiHasilBulan = $pokok * 0.02;
            $pokokBulan = $tenor > 0 ? $pokok / $tenor : 0;
            $totalAngsuranBulan = $pokokBulan + $bagiHasilBulan;
            $totalTagihan = $pokok + ($bagiHasilBulan * $tenor);
        @endphp
        <div class="card border-0 shadow-sm mb-4 bg-light">
            <div class="card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-calculator me-2"></i>Kalkulasi Skim Pembiayaan Syariah</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Nisbah Bagi Hasil</td>
                        <td class="text-end fw-semibold">2.00% / Bulan (Flat)</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Pokok per Bulan</td>
                        <td class="text-end fw-semibold">Rp {{ number_format($pokokBulan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Bagi Hasil per Bulan</td>
                        <td class="text-end fw-semibold">Rp {{ number_format($bagiHasilBulan, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-top">
                        <td class="fw-bold pt-2">Total Angsuran per Bulan</td>
                        <td class="text-end fw-bold text-primary pt-2 fs-5">Rp {{ number_format($totalAngsuranBulan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Pengembalian ({{ $tenor }} bln)</td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($pengajuan->status_pengajuan === 'menunggu')
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-check-circle me-2 text-primary"></i>Tindakan Verifikasi</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <!-- Form Setujui -->
                    <form action="{{ route('admin.pengajuan.setujui', $pengajuan->id) }}" method="POST" class="mb-3" onsubmit="return confirm('Apakah Anda yakin menyetujui pengajuan ini dan mencairkan dana pinjaman?')">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan Persetujuan (Opsional)</label>
                            <textarea name="catatan" rows="2" class="form-control" placeholder="Memenuhi syarat SOP pembiayaan..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100 py-2">
                            <i class="bi bi-check-lg me-1"></i> SETUJUI (ACC) & CAIRKAN PINJAMAN
                        </button>
                    </form>

                    <hr>

                    <!-- Form Tolak -->
                    <form action="{{ route('admin.pengajuan.tolak', $pengajuan->id) }}" method="POST" onsubmit="return confirm('Tolak permohonan pinjaman ini?')">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-danger">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="catatan_verifikasi" rows="2" class="form-control" required placeholder="Sebutkan alasan penolakan..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-1"></i> Tolak Permohonan
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <h6 class="text-muted mb-2">Status Pengajuan Ini</h6>
                    @if($pengajuan->status_pengajuan === 'disetujui')
                        <span class="badge bg-success fs-6 p-2"><i class="bi bi-check-circle me-1"></i> TELAH DISETUJUI</span>
                        <div class="mt-3">
                            <small class="text-muted d-block">Diverifikasi pada: {{ $pengajuan->tgl_verifikasi ? $pengajuan->tgl_verifikasi->format('d/m/Y H:i') : '-' }}</small>
                            <small class="text-muted d-block">Oleh: {{ $pengajuan->petugasVerifikator ? $pengajuan->petugasVerifikator->username : '-' }}</small>
                        </div>
                        @if($pengajuan->pinjaman)
                            <a href="{{ route('admin.pinjaman.show', $pengajuan->pinjaman->id) }}" class="btn btn-primary mt-3">
                                <i class="bi bi-eye me-1"></i> Lihat Kontrak Pinjaman Berjalan
                            </a>
                        @endif
                    @else
                        <span class="badge bg-danger fs-6 p-2"><i class="bi bi-x-circle me-1"></i> TELAH DITOLAK</span>
                        <div class="mt-3 p-3 bg-light rounded text-start">
                            <small class="text-muted d-block fw-semibold">Catatan Penolakan:</small>
                            <p class="mb-0 text-danger">{{ $pengajuan->catatan_verifikasi ?: 'Tidak ada catatan.' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

