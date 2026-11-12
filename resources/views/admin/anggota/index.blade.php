@extends('layouts.app')

@section('title', 'Data Anggota')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Data Anggota Koperasi</h4>
        <small class="text-muted">Kelola data seluruh anggota koperasi syariah</small>
    </div>
    <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Tambah Anggota Baru
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.anggota.index') }}" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Cari nama, no anggota, NIK, atau no HP..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="non-aktif" {{ request('status') === 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                        <th class="ps-3">No. Anggota</th>
                        <th>Nama Lengkap</th>
                        <th>NIK</th>
                        <th>No. HP</th>
                        <th>Pekerjaan</th>
                        <th>Tgl Gabung</th>
                        <th>Total Saldo</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggota as $item)
                        <tr>
                            <td class="ps-3"><strong class="text-primary">{{ $item->no_anggota }}</strong></td>
                            <td>
                                <div class="fw-semibold">{{ $item->nama_lengkap }}</div>
                                <small class="text-muted">{{ $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</small>
                            </td>
                            <td>{{ $item->nik }}</td>
                            <td>{{ $item->no_hp }}</td>
                            <td>{{ $item->pekerjaan }}</td>
                            <td>{{ $item->tgl_gabung ? $item->tgl_gabung->format('d/m/Y') : '-' }}</td>
                            <td>
                                <span class="fw-bold text-success">Rp {{ number_format($item->saldo_total, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($item->status_anggota === 'aktif')
                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.anggota.show', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Detail Profil & Tabungan">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.anggota.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Data">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.anggota.toggle', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ubah status keanggotaan ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Ubah Status">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada data anggota ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($anggota->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $anggota->links() }}
        </div>
    @endif
</div>
@endsection

