@extends('layouts.app')

@section('title', 'Monitoring Simpanan - Pimpinan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('pimpinan.laporan.index') }}" class="text-decoration-none text-muted d-print-none">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Menu Laporan
        </a>
        <h4 class="mb-0 fw-bold mt-2">Monitoring Mutasi Simpanan Koperasi</h4>
        <small class="text-muted">Periode: Bulan {{ DateTime::createFromFormat('!m', (int)$bulan)->format('F') }} {{ $tahun }}</small>
    </div>
    <button onclick="window.print()" class="btn btn-primary d-print-none">
        <i class="bi bi-printer me-1"></i> Cetak Laporan
    </button>
</div>

<div class="card border-0 shadow-sm mb-4 d-print-none">
    <div class="card-body">
        <form method="GET" action="{{ route('pimpinan.laporan.simpanan') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="bulan" class="form-select">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == sprintf('%02d', $m) ? 'selected' : '' }}>
                            Bulan {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="tahun" class="form-control" value="{{ $tahun }}" placeholder="Tahun">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter me-1"></i> Tampilkan</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 text-center bg-white">
            <small class="text-muted text-uppercase fw-semibold">Total Setoran Kas Masuk</small>
            <h4 class="fw-bold text-success mt-2 mb-0">Rp {{ number_format($totalSetor, 0, ',', '.') }}</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 text-center bg-white">
            <small class="text-muted text-uppercase fw-semibold">Total Penarikan Kas Keluar</small>
            <h4 class="fw-bold text-danger mt-2 mb-0">Rp {{ number_format($totalTarik, 0, ',', '.') }}</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 text-center bg-white">
            <small class="text-muted text-uppercase fw-semibold">Surplus Kas Bersih Periode Ini</small>
            <h4 class="fw-bold text-primary mt-2 mb-0">Rp {{ number_format($totalSetor - $totalTarik, 0, ',', '.') }}</h4>
        </div>
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
                        <th>Nama Anggota</th>
                        <th>Jenis Simpanan</th>
                        <th>Tipe</th>
                        <th class="text-end pe-3">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                        <tr>
                            <td class="ps-3"><strong class="text-primary">{{ $t->no_transaksi }}</strong></td>
                            <td>{{ $t->tgl_transaksi->format('d/m/Y H:i') }}</td>
                            <td>{{ $t->anggota->nama_lengkap }} ({{ $t->anggota->no_anggota }})</td>
                            <td>{{ $t->jenisSimpanan->nama_simpanan }}</td>
                            <td>
                                @if($t->jenis_transaksi === 'setor')
                                    <span class="badge bg-success-subtle text-success">Setoran</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 fw-bold {{ $t->jenis_transaksi === 'setor' ? 'text-success' : 'text-danger' }}">
                                {{ $t->jenis_transaksi === 'setor' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

