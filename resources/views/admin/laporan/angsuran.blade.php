@extends('layouts.app')

@section('title', 'Laporan Penerimaan Angsuran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <div>
        <h4 class="mb-0 fw-bold">Laporan Penerimaan Angsuran</h4>
        <small class="text-muted">Rekapitulasi cicilan pokok dan bagi hasil yang diterima kasir</small>
    </div>
    <a href="{{ route('admin.laporan.cetak-angsuran', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="btn btn-primary">
        <i class="bi bi-printer me-1"></i> Cetak Format Dokumen
    </a>
</div>

<div class="card border-0 shadow-sm mb-4 d-print-none">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan.angsuran') }}" class="row g-2 align-items-center">
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

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. Bukti</th>
                        <th>Tanggal Bayar</th>
                        <th>No. Pinjaman</th>
                        <th>Nama Anggota</th>
                        <th>Angsuran Ke</th>
                        <th>Bayar Pokok</th>
                        <th>Bagi Hasil (2%)</th>
                        <th class="text-end pe-3">Total Diterima</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totPokok = 0;
                        $totBagiHasil = 0;
                        $totAll = 0;
                    @endphp
                    @forelse($angsuran as $a)
                        @php
                            $totPokok += $a->bayar_pokok;
                            $totBagiHasil += $a->bayar_bagi_hasil;
                            $totAll += $a->total_bayar;
                        @endphp
                        <tr>
                            <td class="ps-3"><strong class="text-primary">{{ $a->no_bukti_angsuran }}</strong></td>
                            <td>{{ $a->tgl_bayar->format('d/m/Y H:i') }}</td>
                            <td>{{ $a->pinjaman->no_pinjaman }}</td>
                            <td>{{ $a->pinjaman->anggota->nama_lengkap }}</td>
                            <td>Bulan ke-{{ $a->angsuran_ke }}</td>
                            <td>Rp {{ number_format($a->bayar_pokok, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($a->bayar_bagi_hasil, 0, ',', '.') }}</td>
                            <td class="text-end pe-3 fw-bold text-success">Rp {{ number_format($a->total_bayar, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada pembayaran angsuran pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($angsuran->count() > 0)
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="5" class="ps-3 text-uppercase">Total Rekapitulasi:</td>
                            <td>Rp {{ number_format($totPokok, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($totBagiHasil, 0, ',', '.') }}</td>
                            <td class="text-end pe-3 text-success fs-6">Rp {{ number_format($totAll, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

