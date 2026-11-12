@extends('layouts.app')

@section('title', 'Monitoring Pembiayaan - Pimpinan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('pimpinan.laporan.index') }}" class="text-decoration-none text-muted d-print-none">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Menu Laporan
        </a>
        <h4 class="mb-0 fw-bold mt-2">Monitoring Portofolio Pembiayaan / Pinjaman</h4>
        <small class="text-muted">Status pencairan dana pinjaman dan pengembalian angsuran</small>
    </div>
    <button onclick="window.print()" class="btn btn-primary d-print-none">
        <i class="bi bi-printer me-1"></i> Cetak Dokumen
    </button>
</div>

<div class="card border-0 shadow-sm mb-4 d-print-none">
    <div class="card-body">
        <form method="GET" action="{{ route('pimpinan.laporan.pinjaman') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <input type="number" name="tahun" class="form-control" value="{{ $tahun }}" placeholder="Tahun Akad">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="berjalan" {{ $status === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                    <option value="lunas" {{ $status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="macet" {{ $status === 'macet' ? 'selected' : '' }}>Macet</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter me-1"></i> Tampilkan</button>
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
                        <th>Tgl Akad</th>
                        <th>Nama Anggota</th>
                        <th>Pokok</th>
                        <th>Tenor</th>
                        <th>Bagi Hasil 2%</th>
                        <th>Total Tagihan</th>
                        <th>Angsuran Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pinjaman as $p)
                        <tr>
                            <td class="ps-3"><strong class="text-primary">{{ $p->no_pinjaman }}</strong></td>
                            <td>{{ $p->tgl_akad_cair ? $p->tgl_akad_cair->format('d/m/Y') : '-' }}</td>
                            <td>{{ $p->anggota->nama_lengkap }} ({{ $p->anggota->no_anggota }})</td>
                            <td>Rp {{ number_format($p->jumlah_pokok, 0, ',', '.') }}</td>
                            <td>{{ $p->lama_bulan }} Bln</td>
                            <td>Rp {{ number_format($p->total_bagi_hasil, 0, ',', '.') }}</td>
                            <td class="fw-bold">Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}</td>
                            <td>{{ $p->angsuran->count() }}/{{ $p->lama_bulan }}</td>
                            <td>
                                @if($p->status_pinjaman === 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($p->status_pinjaman === 'berjalan')
                                    <span class="badge bg-warning text-dark">Berjalan</span>
                                @else
                                    <span class="badge bg-danger">Macet</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Tidak ada data pembiayaan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

