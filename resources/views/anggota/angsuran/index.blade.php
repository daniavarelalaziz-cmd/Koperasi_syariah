@extends('layouts.app')

@section('title', 'Jadwal Angsuran Saya')

@section('content')
<div class="mb-4">
    <h4 class="mb-0 fw-bold">Jadwal & Riwayat Angsuran</h4>
    <small class="text-muted">Pantau status cicilan bulanan pembiayaan syariah Anda</small>
</div>

@forelse($pinjamanList as $pinjam)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-bold">Kontrak: {{ $pinjam->no_pinjaman }}</h6>
                <small class="text-muted">Pokok: Rp {{ number_format($pinjam->jumlah_pokok, 0, ',', '.') }} | Tenor: {{ $pinjam->lama_bulan }} Bulan | Angsuran: Rp {{ number_format($pinjam->total_angsuran_per_bulan, 0, ',', '.') }}/bulan</small>
            </div>
            <div>
                @if($pinjam->status_pinjaman === 'lunas')
                    <span class="badge bg-success fs-6"><i class="bi bi-check2-circle me-1"></i> LUNAS</span>
                @else
                    <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock me-1"></i> BERJALAN</span>
                    <a href="{{ route('anggota.bayar.angsuran', $pinjam->id) }}" class="btn btn-sm btn-success ms-2">
                        <i class="bi bi-qr-code-scan me-1"></i>Bayar QRIS
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Angsuran Ke</th>
                            <th>Pokok</th>
                            <th>Bagi Hasil (2%)</th>
                            <th>Total Tagihan</th>
                            <th>Tgl Bayar</th>
                            <th>No. Bukti Kwitansi</th>
                            <th class="text-end pe-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $angsuranMap = $pinjam->angsuran->keyBy('angsuran_ke');
                        @endphp
                        @for($i = 1; $i <= $pinjam->lama_bulan; $i++)
                            @php
                                $bayar = $angsuranMap->get($i);
                            @endphp
                            <tr>
                                <td class="ps-3 fw-bold">Bulan ke-{{ $i }}</td>
                                <td>Rp {{ number_format($pinjam->angsuran_pokok_per_bulan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($pinjam->bagi_hasil_per_bulan, 0, ',', '.') }}</td>
                                <td class="fw-semibold">Rp {{ number_format($pinjam->total_angsuran_per_bulan, 0, ',', '.') }}</td>
                                <td>{{ $bayar ? $bayar->tgl_bayar->format('d/m/Y') : '-' }}</td>
                                <td><small class="text-muted">{{ $bayar ? $bayar->no_bukti_angsuran : '-' }}</small></td>
                                <td class="text-end pe-3">
                                    @if($bayar)
                                        <span class="badge bg-success-subtle text-success"><i class="bi bi-check2"></i> Terbayar</span>
                                    @else
                                        @if($i === $pinjam->angsuran->count() + 1 && $pinjam->status_pinjaman === 'berjalan')
                                            <span class="badge bg-danger-subtle text-danger"><i class="bi bi-exclamation-circle me-1"></i>Jatuh Tempo</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-muted">Belum</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@empty
    <div class="card border-0 shadow-sm p-5 text-center text-muted">
        <i class="bi bi-wallet2 fs-1 text-muted mb-2 d-block"></i>
        Anda belum memiliki riwayat pembiayaan atau pinjaman.
    </div>
@endforelse
@endsection

