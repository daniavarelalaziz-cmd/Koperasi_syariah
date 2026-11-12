@extends('layouts.app')

@section('title', 'Edit Data Anggota')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.anggota.show', $anggota->id) }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Anggota
    </a>
    <h4 class="mb-0 fw-bold mt-2">Edit Data Anggota</h4>
    <small class="text-muted">Perbarui informasi profil {{ $anggota->nama_lengkap }} ({{ $anggota->no_anggota }})</small>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.anggota.update', $anggota->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required>
                    @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                    <input type="text" name="nik" maxlength="16" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $anggota->nik) }}" required>
                    @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="L" {{ old('jenis_kelamin', $anggota->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $anggota->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">No. Handphone / WA <span class="text-danger">*</span></label>
                    <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $anggota->no_hp) }}" required>
                    @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status Pernikahan <span class="text-danger">*</span></label>
                    <select name="status_pernikahan" class="form-select @error('status_pernikahan') is-invalid @enderror" required>
                        <option value="Belum Menikah" {{ old('status_pernikahan', $anggota->status_pernikahan) === 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                        <option value="Menikah" {{ old('status_pernikahan', $anggota->status_pernikahan) === 'Menikah' ? 'selected' : '' }}>Menikah</option>
                        <option value="Cerai" {{ old('status_pernikahan', $anggota->status_pernikahan) === 'Cerai' ? 'selected' : '' }}>Cerai</option>
                    </select>
                    @error('status_pernikahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pekerjaan <span class="text-danger">*</span></label>
                    <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" value="{{ old('pekerjaan', $anggota->pekerjaan) }}" required>
                    @error('pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Penghasilan Bulanan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" step="any" name="penghasilan_per_bulan" class="form-control @error('penghasilan_per_bulan') is-invalid @enderror" value="{{ old('penghasilan_per_bulan', $anggota->penghasilan_per_bulan) }}" required>
                    @error('penghasilan_per_bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat', $anggota->alamat) }}</textarea>
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.anggota.show', $anggota->id) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

