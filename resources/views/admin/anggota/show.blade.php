@extends('layouts.app')

@section('title', 'Detail Anggota - ' . $anggota->nama_lengkap)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.anggota.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Anggota
    </a>
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div>
            <h4 class="mb-0 fw-bold">{{ $anggota->nama_lengkap }}</h4>
            <span class="badge bg-primary me-2">{{ $anggota->no_anggota }}</span>
            @if($anggota->status_anggota === 'aktif')
                <span class="badge bg-success">Status: Aktif</span>
            @else
                <span class="badge bg-danger">Status: Non-Aktif</span>
            @endif
        </div>
        <div>
            <a href="{{ route('admin.anggota.edit', $anggota->id) }}" class="btn btn-outline-secondary me-1">
                <i class="bi bi-pencil me-1"></i> Edit Profil
            </a>
            <a href="{{ route('admin.simpanan.create') }}?anggota_id={{ $anggota->id }}" class="btn btn-success">
                <i class="bi bi-cash-stack me-1"></i> Transaksi Simpanan
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <small class="text-muted text-uppercase fw-semibold">Total Saldo Simpanan</small>
            <h3 class="fw-bold text-success mt-2 mb-0">Rp {{ number_format($anggota->saldo_total, 0, ',', '.') }}</h3>
        </div>
    </div>
    @foreach($saldoPerJenis as $kode => $info)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <small class="text-muted text-uppercase fw-semibold">{{ $info['nama'] }}</small>
                <h4 class="fw-bold text-primary mt-2 mb-0">Rp {{ number_format($info['saldo'], 0, ',', '.') }}</h4>
            </div>
        </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-3">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-profil">
                    <i class="bi bi-person me-1"></i> Profil Anggota
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-simpanan">
                    <i class="bi bi-journal-bookmark me-1"></i> Mutasi Simpanan (Buku Tabungan)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-pinjaman">
                    <i class="bi bi-wallet2 me-1"></i> Riwayat Pembiayaan / Pinjaman
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body p-4">
        <div class="tab-content">
            <!-- TAB PROFIL -->
            <div class="tab-pane fade show active" id="tab-profil">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">NIK</small>
                        <span class="fw-semibold">{{ $anggota->nik }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Jenis Kelamin</small>
                        <span class="fw-semibold">{{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">No. Handphone</small>
                        <span class="fw-semibold">{{ $anggota->no_hp }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Status Pernikahan</small>
                        <span class="fw-semibold">{{ $anggota->status_pernikahan }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Pekerjaan</small>
                        <span class="fw-semibold">{{ $anggota->pekerjaan }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Penghasilan Bulanan</small>
                        <span class="fw-semibold">Rp {{ number_format($anggota->penghasilan_per_bulan, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Tanggal Bergabung</small>
                        <span class="fw-semibold">{{ $anggota->tgl_gabung ? $anggota->tgl_gabung->format('d F Y') : '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Username Login</small>
                        <span class="fw-semibold">{{ $anggota->user ? $anggota->user->username : '-' }}</span>
                    </div>
                    <div class="col-md-12">
                        <small class="text-muted d-block">Alamat Lengkap</small>
                        <span class="fw-semibold">{{ $anggota->alamat }}</span>
                    </div>
                </div>
            </div>

            <!-- TAB SIMPANAN -->
            <div class="tab-pane fade" id="tab-simpanan">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No. Transaksi</th>
                                <th>Tanggal</th>
                                <th>Jenis Simpanan</th>
                                <th>Transaksi</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggota->transaksiSimpanan->sortByDesc('tgl_transaksi') as $trx)
                                <tr>
                                    <td><strong class="text-primary">{{ $trx->no_transaksi }}</strong></td>
                                    <td>{{ $trx->tgl_transaksi->format('d/m/Y H:i') }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $trx->jenisSimpanan->nama_simpanan }}</span></td>
                                    <td>
                                        @if($trx->jenis_transaksi === 'setor')
                                            <span class="badge bg-success-subtle text-success">Setor</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Tarik</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold {{ $trx->jenis_transaksi === 'setor' ? 'text-success' : 'text-danger' }}">
                                        {{ $trx->jenis_transaksi === 'setor' ? '+' : '-' }} Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td><small class="text-muted">{{ $trx->keterangan }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">Belum ada riwayat transaksi simpanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB PINJAMAN -->
            <div class="tab-pane fade" id="tab-pinjaman">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No. Pinjaman</th>
                                <th>Tgl Akad</th>
                                <th>Pokok</th>
                                <th>Tenor</th>
                                <th>Bagi Hasil 2%/Bln</th>
                                <th>Angsuran/Bln</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggota->pinjaman as $pinjam)
                                <tr>
                                    <td><strong>{{ $pinjam->no_pinjaman }}</strong></td>
                                    <td>{{ $pinjam->tgl_akad_cair->format('d/m/Y') }}</td>
                                    <td>Rp {{ number_format($pinjam->jumlah_pokok, 0, ',', '.') }}</td>
                                    <td>{{ $pinjam->lama_bulan }} Bulan</td>
                                    <td>Rp {{ number_format($pinjam->bagi_hasil_per_bulan, 0, ',', '.') }}</td>
                                    <td class="fw-bold text-primary">Rp {{ number_format($pinjam->total_angsuran_per_bulan, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="progress" style="height: 16px;">
                                            @php
                                                $persen = ($pinjam->angsuran->count() / $pinjam->lama_bulan) * 100;
                                            @endphp
                                            <div class="progress-bar bg-success" style="width: {{ $persen }}%">
                                                {{ $pinjam->angsuran->count() }}/{{ $pinjam->lama_bulan }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($pinjam->status_pinjaman === 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @elseif($pinjam->status_pinjaman === 'berjalan')
                                            <span class="badge bg-warning text-dark">Berjalan</span>
                                        @else
                                            <span class="badge bg-danger">Macet</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.pinjaman.show', $pinjam->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-3 text-muted">Belum ada riwayat pembiayaan/pinjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

