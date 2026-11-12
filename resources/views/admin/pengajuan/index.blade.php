@extends('layouts.app')

@section('title', 'Daftar Pengajuan Pinjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Pengajuan Pembiayaan / Pinjaman</h4>
        <small class="text-muted">Daftar permohonan pinjaman anggota yang perlu diverifikasi dan di-ACC</small>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pengajuan.index') }}" class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui (ACC)</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. Pengajuan</th>
                        <th>Tanggal</th>
                        <th>Nama Anggota</th>
                        <th>Nominal Diajukan</th>
                        <th>Tenor</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $p)
                        <tr>
                            <td class="ps-3"><strong class="text-primary">{{ $p->no_pengajuan }}</strong></td>
                            <td>{{ $p->tgl_pengajuan->format('d/m/Y') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $p->anggota->nama_lengkap }}</div>
                                <small class="text-muted">{{ $p->anggota->no_anggota }}</small>
                            </td>
                            <td class="fw-bold">Rp {{ number_format($p->jumlah_pengajuan, 0, ',', '.') }}</td>
                            <td>{{ $p->jangka_waktu_bulan }} Bulan</td>
                            <td><small class="text-muted">{{ Str::limit($p->tujuan_pinjaman, 40) }}</small></td>
                            <td>
                                @if($p->status_pengajuan === 'menunggu')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Menunggu</span>
                                @elseif($p->status_pengajuan === 'disetujui')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.pengajuan.show', $p->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-search me-1"></i> Periksa / Verifikasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada pengajuan pinjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pengajuan->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $pengajuan->links() }}
        </div>
    @endif
</div>
@endsection

