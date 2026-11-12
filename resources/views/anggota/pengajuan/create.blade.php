@extends('layouts.app')

@section('title', 'Formulir Pengajuan Pembiayaan')

@section('content')
<div class="mb-4">
    <a href="{{ route('anggota.pengajuan.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Pengajuan
    </a>
    <h4 class="mb-0 fw-bold mt-2">Formulir Permohonan Pembiayaan Syariah</h4>
    <small class="text-muted">Isi data permohonan pinjaman modal atau multiguna secara lengkap</small>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('anggota.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Nominal Pinjaman (Rp) <span class="text-danger">*</span></label>
                        <input type="number" step="100000" min="500000" name="jumlah_pengajuan" id="input-pokok" class="form-control form-control-lg @error('jumlah_pengajuan') is-invalid @enderror" value="{{ old('jumlah_pengajuan', 1000000) }}" required placeholder="Contoh: 1000000">
                        @error('jumlah_pengajuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jangka Waktu Angsuran (Tenor) <span class="text-danger">*</span></label>
                        <select name="jangka_waktu_bulan" id="input-tenor" class="form-select form-select-lg @error('jangka_waktu_bulan') is-invalid @enderror" required>
                            <option value="6" {{ old('jangka_waktu_bulan') == 6 ? 'selected' : '' }}>6 Bulan</option>
                            <option value="10" {{ old('jangka_waktu_bulan', 10) == 10 ? 'selected' : '' }}>10 Bulan (Rekomendasi)</option>
                            <option value="12" {{ old('jangka_waktu_bulan') == 12 ? 'selected' : '' }}>12 Bulan (1 Tahun)</option>
                            <option value="18" {{ old('jangka_waktu_bulan') == 18 ? 'selected' : '' }}>18 Bulan</option>
                            <option value="24" {{ old('jangka_waktu_bulan') == 24 ? 'selected' : '' }}>24 Bulan (2 Tahun)</option>
                        </select>
                        @error('jangka_waktu_bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tujuan / Keperluan Pinjaman <span class="text-danger">*</span></label>
                        <textarea name="tujuan_pinjaman" rows="3" class="form-control @error('tujuan_pinjaman') is-invalid @enderror" required placeholder="Jelaskan kebutuhan dana (contoh: modal beli pupuk dan bibit pertanian...)">{{ old('tujuan_pinjaman') }}</textarea>
                        @error('tujuan_pinjaman')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <h6 class="fw-bold text-primary mt-4 mb-3"><i class="bi bi-paperclip me-2"></i>Upload Berkas Syarat</h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto KTP Pemohon / Suami-Istri (JPG, PNG, PDF maks 2MB)</label>
                        <input type="file" name="berkas_ktp" class="form-control @error('berkas_ktp') is-invalid @enderror">
                        @error('berkas_ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Kartu Keluarga (KK) (JPG, PNG, PDF maks 2MB)</label>
                        <input type="file" name="berkas_kk" class="form-control @error('berkas_kk') is-invalid @enderror">
                        @error('berkas_kk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('anggota.pengajuan.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Kirim Permohonan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SIMULASI REAL-TIME -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm bg-light sticky-top" style="top: 80px;">
            <div class="card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-calculator me-2"></i>Simulasi Angsuran Bulanan</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Nominal Pinjaman</td>
                        <td class="text-end fw-semibold" id="sim-pokok">Rp 1.000.000</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tenor Angsuran</td>
                        <td class="text-end fw-semibold" id="sim-tenor">10 Bulan</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Cicilan Pokok/Bulan</td>
                        <td class="text-end fw-semibold" id="sim-cicilan-pokok">Rp 100.000</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Bagi Hasil 2%/Bulan</td>
                        <td class="text-end fw-semibold text-info" id="sim-bagihasil">Rp 20.000</td>
                    </tr>
                    <tr class="border-top">
                        <td class="fw-bold pt-2">Angsuran per Bulan</td>
                        <td class="text-end fw-bold text-primary pt-2 fs-5" id="sim-total-angsuran">Rp 120.000</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Pengembalian</td>
                        <td class="text-end fw-bold text-success" id="sim-total-tagihan">Rp 1.200.000</td>
                    </tr>
                </table>

                <div class="alert alert-info mt-3 mb-0 small">
                    <i class="bi bi-info-circle me-1"></i>
                    Sistem pembiayaan syariah menggunakan nisbah bagi hasil flat 2% per bulan sesuai akad yang disepakati di awal tanpa riba.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputPokok = document.getElementById('input-pokok');
        const inputTenor = document.getElementById('input-tenor');

        function formatRupiah(num) {
            return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function hitungSimulasi() {
            const pokok = parseFloat(inputPokok.value) || 0;
            const tenor = parseInt(inputTenor.value) || 1;

            const bagiHasilBln = pokok * 0.02;
            const pokokBln = tenor > 0 ? (pokok / tenor) : 0;
            const totalAngsuran = pokokBln + bagiHasilBln;
            const totalTagihan = pokok + (bagiHasilBln * tenor);

            document.getElementById('sim-pokok').innerText = formatRupiah(pokok);
            document.getElementById('sim-tenor').innerText = tenor + ' Bulan';
            document.getElementById('sim-cicilan-pokok').innerText = formatRupiah(pokokBln);
            document.getElementById('sim-bagihasil').innerText = formatRupiah(bagiHasilBln);
            document.getElementById('sim-total-angsuran').innerText = formatRupiah(totalAngsuran);
            document.getElementById('sim-total-tagihan').innerText = formatRupiah(totalTagihan);
        }

        inputPokok.addEventListener('input', hitungSimulasi);
        inputTenor.addEventListener('change', hitungSimulasi);
        hitungSimulasi();
    });
</script>
@endsection

