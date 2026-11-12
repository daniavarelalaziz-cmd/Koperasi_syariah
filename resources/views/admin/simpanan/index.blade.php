@extends('layouts.app')

@section('title', 'Transaksi Simpanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Transaksi Simpanan</h4>
        <small class="text-muted">Pencatatan mutasi kas simpanan pokok, wajib, dan sukarela</small>
    </div>
    <a href="{{ route('admin.simpanan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Transaksi Setor / Tarik
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.simpanan.index') }}" class="row g-2">
            <div class="col-md-3">
                <select name="anggota_id" class="form-select">
                    <option value="">-- Semua Anggota --</option>
                    @foreach($anggotaList as $a)
                        <option value="{{ $a->id }}" {{ request('anggota_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->no_anggota }} - {{ $a->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="jenis_simpanan_id" class="form-select">
                    <option value="">-- Semua Jenis --</option>
                    @foreach($jenisList as $j)
                        <option value="{{ $j->id }}" {{ request('jenis_simpanan_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_simpanan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="jenis_transaksi" class="form-select">
                    <option value="">-- Semua Tipe --</option>
                    <option value="setor" {{ request('jenis_transaksi') === 'setor' ? 'selected' : '' }}>Setoran (+)</option>
                    <option value="tarik" {{ request('jenis_transaksi') === 'tarik' ? 'selected' : '' }}>Penarikan (-)</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}" placeholder="Dari Tgl">
            </div>
            <div class="col-md-2">
                <input type="date" name="tgl_akhir" class="form-control" value="{{ request('tgl_akhir') }}" placeholder="Sampai Tgl">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search"></i></button>
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
                        <th class="ps-3">No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Anggota</th>
                        <th>Jenis Simpanan</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Petugas</th>
                        <th class="text-end pe-3">Struk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                        <tr>
                            <td class="ps-3"><strong class="text-primary">{{ $t->no_transaksi }}</strong></td>
                            <td>{{ $t->tgl_transaksi->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $t->anggota->nama_lengkap }}</div>
                                <small class="text-muted">{{ $t->anggota->no_anggota }}</small>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $t->jenisSimpanan->nama_simpanan }}</span></td>
                            <td>
                                @if($t->jenis_transaksi === 'setor')
                                    <span class="badge bg-success-subtle text-success">Setor</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Tarik</span>
                                @endif
                            </td>
                            <td class="fw-bold {{ $t->jenis_transaksi === 'setor' ? 'text-success' : 'text-danger' }}">
                                {{ $t->jenis_transaksi === 'setor' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                            </td>
                            <td><small class="text-muted">{{ $t->petugas ? $t->petugas->username : '-' }}</small></td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.simpanan.show', $t->id) }}" class="btn btn-sm btn-outline-primary" title="Cetak Struk">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada transaksi simpanan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transaksi->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $transaksi->links() }}
        </div>
    @endif
</div>
@endsection

