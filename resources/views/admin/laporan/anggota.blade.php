@extends('layouts.app')

@section('title', 'Laporan Rekapitulasi Anggota & Simpanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Rekapitulasi Anggota & Posisi Saldo</h4>
        <small class="text-muted">Daftar anggota aktif beserta akumulasi saldo simpanan pada kas koperasi</small>
    </div>
    <button onclick="window.print()" class="btn btn-primary d-print-none">
        <i class="bi bi-printer me-1"></i> Cetak Laporan
    </button>
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
                        <th class="text-end pe-3">Total Saldo Simpanan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @forelse($anggota as $a)
                        @php $grandTotal += $a->saldo_total; @endphp
                        <tr>
                            <td class="ps-3"><strong class="text-primary">{{ $a->no_anggota }}</strong></td>
                            <td>{{ $a->nama_lengkap }}</td>
                            <td>{{ $a->nik }}</td>
                            <td>{{ $a->no_hp }}</td>
                            <td>{{ $a->pekerjaan }}</td>
                            <td>{{ $a->tgl_gabung ? $a->tgl_gabung->format('d/m/Y') : '-' }}</td>
                            <td class="text-end pe-3 fw-bold text-success">
                                Rp {{ number_format($a->saldo_total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data anggota.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($anggota->count() > 0)
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="6" class="ps-3 text-uppercase">Total Simpanan Keseluruhan Anggota:</td>
                            <td class="text-end pe-3 text-success fs-6">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

