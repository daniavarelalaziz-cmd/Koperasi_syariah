@extends('layouts.app')

@section('title', 'Tambah Anggota Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.anggota.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Anggota
    </a>
    <h4 class="mb-0 fw-bold mt-2">Pendaftaran Anggota Baru</h4>
    <small class="text-muted">Formulir pendaftaran anggota + pembuatan akun login + setoran pokok otomatis (Rp 20.000)</small>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.anggota.store') }}" method="POST">
            @csrf
            
            <h5 class="fw-bold text-primary mb-3"><i class="bi bi-person-vcard me-2"></i>Data Identitas Anggota</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Ahmad Dahlan">
                    @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                    <input type="text" name="nik" maxlength="16" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" required placeholder="16 digit angka KTP">
                    @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">No. Handphone / WhatsApp <span class="text-danger">*</span></label>
                    <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx">
                    @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status Pernikahan <span class="text-danger">*</span></label>
                    <select name="status_pernikahan" class="form-select @error('status_pernikahan') is-invalid @enderror" required>
                        <option value="Belum Menikah" {{ old('status_pernikahan') === 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                        <option value="Menikah" {{ old('status_pernikahan') === 'Menikah' ? 'selected' : '' }}>Menikah</option>
                        <option value="Cerai" {{ old('status_pernikahan') === 'Cerai' ? 'selected' : '' }}>Cerai</option>
                    </select>
                    @error('status_pernikahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pekerjaan <span class="text-danger">*</span></label>
                    <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" value="{{ old('pekerjaan') }}" required placeholder="Contoh: Petani / Wiraswasta / PNS">
                    @error('pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Penghasilan Rata-rata per Bulan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" step="any" name="penghasilan_per_bulan" class="form-control @error('penghasilan_per_bulan') is-invalid @enderror" value="{{ old('penghasilan_per_bulan', 0) }}" required>
                    @error('penghasilan_per_bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror" required placeholder="Alamat domisili lengkap...">{{ old('alamat') }}</textarea>
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <h5 class="fw-bold text-primary mb-3"><i class="bi bi-shield-lock me-2"></i>Akun Akses Portal Anggota</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Username Login <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required placeholder="Huruf kecil/angka tanpa spasi">
                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimal 6 karakter">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="alert alert-info d-flex align-items-center mb-4">
                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>Ketentuan Biaya Pendaftaran Koperasi Syariah:</strong><br>
                    Sistem akan secara otomatis mencatat <strong>Simpanan Pokok sebesar Rp 20.000</strong> sebagai saldo awal pendaftaran anggota sesuai AD/ART.
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Simpan & Daftarkan</button>
            </div>
        </form>
    </div>
</div>
@endsection

