@extends('layouts.app')

@section('title', 'Daftar Pinjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Kontrak Pembiayaan / Pinjaman</h4>
        <small class="text-muted">Daftar seluruh pinjaman anggota berjalan, lunas, dan pemantauan angsuran</small>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pinjaman.index') }}" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Cari nama anggota atau no pinjaman..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="berjalan" {{ request('status') === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                    <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="macet" {{ request('status') === 'macet' ? 'selected' : '' }}>Macet</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.pinjaman.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                        <th class="ps-3">No. Pinjaman</th>
                        <th>Anggota</th>
                        <th>Tgl Akad Cair</th>
                        <th>Pokok Pinjaman</th>
                        <th>Tenor</th>
                        <th>Angsuran / Bln</th>
                        <th>Progres Bayar</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pinjaman as $p)
                        <tr>
                            <td class="ps-3"><strong class="text-primary">{{ $p->no_pinjaman }}</strong></td>
                            <td>
                                <div class="fw-semibold">{{ $p->anggota->nama_lengkap }}</div>
                                <small class="text-muted">{{ $p->anggota->no_anggota }}</small>
                            </td>
                            <td>{{ $p->tgl_akad_cair ? $p->tgl_akad_cair->format('d/m/Y') : '-' }}</td>
                            <td class="fw-bold">Rp {{ number_format($p->jumlah_pokok, 0, ',', '.') }}</td>
                            <td>{{ $p->lama_bulan }} Bulan</td>
                            <td class="fw-bold text-primary">Rp {{ number_format($p->total_angsuran_per_bulan, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $sudah = $p->angsuran->count();
                                    $persen = ($sudah / $p->lama_bulan) * 100;
                                @endphp
                                <div class="progress" style="height: 18px;">
                                    <div class="progress-bar bg-success fw-semibold" style="width: {{ $persen }}%">
                                        {{ $sudah }}/{{ $p->lama_bulan }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($p->status_pinjaman === 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($p->status_pinjaman === 'berjalan')
                                    <span class="badge bg-warning text-dark">Berjalan</span>
                                @else
                                    <span class="badge bg-danger">Macet</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.pinjaman.show', $p->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> Detail & Angsuran
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada kontrak pinjaman ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pinjaman->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $pinjaman->links() }}
        </div>
    @endif
</div>
@endsection

