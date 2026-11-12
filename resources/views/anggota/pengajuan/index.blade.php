@extends('layouts.app')

@section('title', 'Pengajuan Pembiayaan Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Pengajuan Pembiayaan / Pinjaman</h4>
        <small class="text-muted">Riwayat permohonan pembiayaan syariah Anda</small>
    </div>
    <a href="{{ route('anggota.pengajuan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Ajukan Pembiayaan Baru
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. Pengajuan</th>
                        <th>Tgl Pengajuan</th>
                        <th>Nominal</th>
                        <th>Tenor</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th>Catatan Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $p)
                        <tr>
                            <td class="ps-3"><strong class="text-primary">{{ $p->no_pengajuan }}</strong></td>
                            <td>{{ $p->tgl_pengajuan->format('d/m/Y') }}</td>
                            <td class="fw-bold">Rp {{ number_format($p->jumlah_pengajuan, 0, ',', '.') }}</td>
                            <td>{{ $p->jangka_waktu_bulan }} Bulan</td>
                            <td><small class="text-muted">{{ Str::limit($p->tujuan_pinjaman, 50) }}</small></td>
                            <td>
                                @if($p->status_pengajuan === 'menunggu')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Menunggu Verifikasi</span>
                                @elseif($p->status_pengajuan === 'disetujui')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui (ACC)</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $p->catatan_verifikasi ?: '-' }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum pernah mengajukan pembiayaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

