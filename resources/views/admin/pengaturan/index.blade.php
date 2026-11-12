@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="bi bi-gear-fill me-2 text-primary"></i>Pengaturan Sistem</h4>
            <p class="text-muted mb-0">Konfigurasi koperasi dan pembayaran QRIS</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.pengaturan.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            {{-- Informasi Koperasi --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-building me-2 text-primary"></i>Informasi Koperasi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Koperasi <span class="text-danger">*</span></label>
                            <input type="text" name="koperasi_nama" class="form-control @error('koperasi_nama') is-invalid @enderror"
                                value="{{ old('koperasi_nama', $settings->get('koperasi_nama')?->value ?? '') }}"
                                placeholder="Nama resmi koperasi" required>
                            @error('koperasi_nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="koperasi_alamat" class="form-control" rows="3"
                                placeholder="Alamat lengkap koperasi">{{ old('koperasi_alamat', $settings->get('koperasi_alamat')?->value ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. Telepon</label>
                            <input type="text" name="koperasi_telp" class="form-control"
                                value="{{ old('koperasi_telp', $settings->get('koperasi_telp')?->value ?? '') }}"
                                placeholder="Nomor telepon / WhatsApp">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pengaturan QRIS --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-qr-code me-2 text-success"></i>Pengaturan QRIS
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info d-flex align-items-start gap-2 py-2 mb-3">
                            <i class="bi bi-info-circle-fill mt-1 flex-shrink-0"></i>
                            <div class="small">
                                <strong>Cara mendapatkan QRIS String:</strong><br>
                                Buka aplikasi bank/e-wallet Anda → Tampilkan QR Code → Klik <em>Bagikan</em> → Salin teks QR (biasanya diawali dengan <code>000201...</code>).
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Merchant QRIS</label>
                            <input type="text" name="qris_nama_merchant" class="form-control"
                                value="{{ old('qris_nama_merchant', $settings->get('qris_nama_merchant')?->value ?? '') }}"
                                placeholder="Nama yang muncul di aplikasi pembayaran">
                            <div class="form-text">Nama yang muncul di layar anggota saat scan QR.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">QRIS String</label>
                            <textarea name="qris_string" id="qris_string" class="form-control font-monospace"
                                rows="5" placeholder="Tempelkan string QRIS di sini (diawali 000201...)">{{ old('qris_string', $qrisString) }}</textarea>
                            <div class="form-text">
                                String mentah QRIS dari bank/e-wallet Anda. Jangan tambahkan spasi atau baris baru.
                            </div>
                        </div>

                        {{-- Preview QR --}}
                        <div id="qr-preview-wrapper" class="{{ empty($qrisString) ? 'd-none' : '' }}">
                            <label class="form-label fw-semibold">Preview QR Code</label>
                            <div class="border rounded p-3 text-center bg-white">
                                <div id="qr-preview">
                                    @if(!empty($qrisString))
                                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->errorCorrection('M')->generate($qrisString) !!}
                                    @endif
                                </div>
                                <p class="text-muted small mb-0 mt-2">QR Code statis (tanpa nominal)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Simpan --}}
        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-2"></i>Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Live preview saat user mengetik/paste QRIS string
document.getElementById('qris_string').addEventListener('input', function() {
    const val = this.value.trim();
    const wrapper = document.getElementById('qr-preview-wrapper');
    if (val.startsWith('000201')) {
        wrapper.classList.remove('d-none');
        document.getElementById('qr-preview').innerHTML = '<p class="text-muted small">Simpan pengaturan untuk melihat preview QR terbaru.</p>';
    } else if (val === '') {
        wrapper.classList.add('d-none');
    }
});
</script>
@endpush

