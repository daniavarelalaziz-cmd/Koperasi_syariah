@extends('layouts.app')

@section('title', 'Detail Pinjaman - ' . $pinjaman->no_pinjaman)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.pinjaman.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pinjaman
    </a>
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div>
            <h4 class="mb-0 fw-bold">Kontrak Pembiayaan: {{ $pinjaman->no_pinjaman }}</h4>
            <span class="badge bg-primary">{{ $pinjaman->anggota->nama_lengkap }} ({{ $pinjaman->anggota->no_anggota }})</span>
            @if($pinjaman->status_pinjaman === 'lunas')
                <span class="badge bg-success">Lunas</span>
            @elseif($pinjaman->status_pinjaman === 'berjalan')
                <span class="badge bg-warning text-dark">Sedang Berjalan</span>
            @else
                <span class="badge bg-danger">Macet</span>
            @endif
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center bg-white">
            <small class="text-muted text-uppercase fw-semibold">Pokok Pembiayaan</small>
            <h4 class="fw-bold text-dark mt-2 mb-0">Rp {{ number_format($pinjaman->jumlah_pokok, 0, ',', '.') }}</h4>
            <small class="text-muted">Tenor: {{ $pinjaman->lama_bulan }} Bulan</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center bg-white">
            <small class="text-muted text-uppercase fw-semibold">Bagi Hasil 2%/Bulan</small>
            <h4 class="fw-bold text-info mt-2 mb-0">Rp {{ number_format($pinjaman->bagi_hasil_per_bulan, 0, ',', '.') }}</h4>
            <small class="text-muted">Total: Rp {{ number_format($pinjaman->total_bagi_hasil, 0, ',', '.') }}</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center bg-white">
            <small class="text-muted text-uppercase fw-semibold">Angsuran / Bulan</small>
            <h4 class="fw-bold text-primary mt-2 mb-0">Rp {{ number_format($pinjaman->total_angsuran_per_bulan, 0, ',', '.') }}</h4>
            <small class="text-muted">Pokok + Bagi Hasil</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center bg-white">
            <small class="text-muted text-uppercase fw-semibold">Total Tagihan Pembiayaan</small>
            <h4 class="fw-bold text-success mt-2 mb-0">Rp {{ number_format($pinjaman->total_tagihan, 0, ',', '.') }}</h4>
            <small class="text-muted">Lunas: {{ $pinjaman->angsuran->count() }}/{{ $pinjaman->lama_bulan }} Kali</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2 text-primary"></i>Tabel Jadwal & Riwayat Pembayaran Angsuran</h6>
                <span class="badge bg-light text-dark border">
                    Sisa Pokok: Rp {{ number_format($pinjaman->sisa_pokok, 0, ',', '.') }}
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Angsuran Ke</th>
                                <th>Pokok</th>
                                <th>Bagi Hasil</th>
                                <th>Total Angsuran</th>
                                <th>Tgl Bayar</th>
                                <th>No. Bukti</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Struk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $angsuranMap = $pinjaman->angsuran->keyBy('angsuran_ke');
                            @endphp
                            @for($i = 1; $i <= $pinjaman->lama_bulan; $i++)
                                @php
                                    $dataBayar = $angsuranMap->get($i);
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-bold">Bulan ke-{{ $i }}</td>
                                    <td>Rp {{ number_format($pinjaman->angsuran_pokok_per_bulan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($pinjaman->bagi_hasil_per_bulan, 0, ',', '.') }}</td>
                                    <td class="fw-semibold">Rp {{ number_format($pinjaman->total_angsuran_per_bulan, 0, ',', '.') }}</td>
                                    <td>
                                        @if($dataBayar)
                                            <span class="text-muted">{{ $dataBayar->tgl_bayar->format('d/m/Y') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($dataBayar)
                                            <small class="text-primary fw-semibold">{{ $dataBayar->no_bukti_angsuran }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($dataBayar)
                                            <span class="badge bg-success-subtle text-success"><i class="bi bi-check2"></i> Lunas</span>
                                        @else
                                            @if($i === $pinjaman->angsuran->count() + 1 && $pinjaman->status_pinjaman === 'berjalan')
                                                <span class="badge bg-warning-subtle text-warning">Jatuh Tempo</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-muted">Belum</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        @if($dataBayar)
                                            <a href="{{ route('admin.angsuran.show', $dataBayar->id) }}" class="btn btn-sm btn-outline-primary" title="Cetak Struk">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if($pinjaman->status_pinjaman === 'berjalan')
            @php
                $nextKe = $pinjaman->angsuran->count() + 1;
            @endphp
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-cash-coin me-2"></i>Bayar Angsuran Ke-{{ $nextKe }}
                    </h5>
                    <form action="{{ route('admin.angsuran.store') }}" method="POST" onsubmit="return confirm('Proses pembayaran angsuran ke-{{ $nextKe }}?')">
                        @csrf
                        <input type="hidden" name="pinjaman_id" value="{{ $pinjaman->id }}">

                        <div class="mb-3">
                            <label class="form-label text-muted small mb-1">Angsuran Pokok</label>
                            <input type="text" class="form-control" value="Rp {{ number_format($pinjaman->angsuran_pokok_per_bulan, 0, ',', '.') }}" readonly disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small mb-1">Bagi Hasil 2%</label>
                            <input type="text" class="form-control" value="Rp {{ number_format($pinjaman->bagi_hasil_per_bulan, 0, ',', '.') }}" readonly disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark mb-1">Total Tagihan Yang Harus Dibayar</label>
                            <input type="text" class="form-control form-control-lg fw-bold text-primary" value="Rp {{ number_format($pinjaman->total_angsuran_per_bulan, 0, ',', '.') }}" readonly disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small mb-1">Catatan / Keterangan (Opsional)</label>
                            <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Pembayaran tepat waktu">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Terima Pembayaran Angsuran
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm text-center p-4">
                <i class="bi bi-patch-check-fill fs-1 text-success mb-2"></i>
                <h5 class="fw-bold text-success">Pembiayaan Sudah Lunas</h5>
                <small class="text-muted">Seluruh tagihan pokok dan bagi hasil telah diselesaikan anggota.</small>
                @if($pinjaman->tgl_lunas)
                    <div class="mt-2 text-muted small">Tanggal Pelunasan: {{ $pinjaman->tgl_lunas->format('d/m/Y') }}</div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

