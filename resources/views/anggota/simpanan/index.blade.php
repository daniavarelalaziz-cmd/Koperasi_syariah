@extends('layouts.app')

@section('title', 'Buku Tabungan Simpanan Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Buku Tabungan Digital</h4>
        <small class="text-muted">Catatan mutasi simpanan Anda pada Koperasi Syariah Punggur Inti Sejahtera</small>
    </div>
    <div class="d-flex gap-2">
        @php $jenisSimpananList = \App\Models\JenisSimpanan::all(); @endphp
        <div class="dropdown d-print-none">
            <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-qr-code-scan me-1"></i>Bayar QRIS
            </button>
            <ul class="dropdown-menu">
                @foreach($jenisSimpananList as $js)
                    <li>
                        <a class="dropdown-item" href="{{ route('anggota.bayar.simpanan', $js->id) }}">
                            <i class="bi bi-wallet me-2"></i>{{ $js->nama_simpanan }}
                            @if($js->nominal_standar > 0)
                                <small class="text-muted">(Rp {{ number_format($js->nominal_standar, 0, ',', '.') }})</small>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <button onclick="window.print()" class="btn btn-outline-primary d-print-none">
            <i class="bi bi-printer me-1"></i> Cetak Rekening Koran
        </button>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 bg-white">
            <small class="text-muted text-uppercase fw-semibold">Total Saldo Tersedia</small>
            <h3 class="fw-bold text-success mt-2 mb-0">Rp {{ number_format($anggota->saldo_total, 0, ',', '.') }}</h3>
        </div>
    </div>
    @foreach($saldoPerJenis as $info)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3 bg-white">
                <small class="text-muted text-uppercase fw-semibold">{{ $info['nama'] }}</small>
                <h4 class="fw-bold text-primary mt-2 mb-0">Rp {{ number_format($info['saldo'], 0, ',', '.') }}</h4>
            </div>
        </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Mutasi Simpanan</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Jenis Simpanan</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                        <tr>
                            <td class="ps-3"><strong class="text-primary">{{ $t->no_transaksi }}</strong></td>
                            <td>{{ $t->tgl_transaksi->format('d/m/Y H:i') }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $t->jenisSimpanan->nama_simpanan }}</span></td>
                            <td>
                                @if($t->jenis_transaksi === 'setor')
                                    <span class="badge bg-success-subtle text-success">Setoran</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="fw-bold {{ $t->jenis_transaksi === 'setor' ? 'text-success' : 'text-danger' }}">
                                {{ $t->jenis_transaksi === 'setor' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                            </td>
                            <td><small class="text-muted">{{ $t->keterangan }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada catatan mutasi tabungan.</td>
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

