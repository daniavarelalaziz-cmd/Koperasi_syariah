@extends('layouts.app')

@section('title', 'Input Transaksi Simpanan')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.simpanan.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Simpanan
    </a>
    <h4 class="mb-0 fw-bold mt-2">Input Transaksi Simpanan</h4>
    <small class="text-muted">Pencatatan setoran atau penarikan simpanan anggota</small>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.simpanan.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Anggota <span class="text-danger">*</span></label>
                        <select name="anggota_id" id="select-anggota" class="form-select @error('anggota_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($anggotaList as $a)
                                <option value="{{ $a->id }}" {{ old('anggota_id', request('anggota_id')) == $a->id ? 'selected' : '' }}>
                                    {{ $a->no_anggota }} - {{ $a->nama_lengkap }} (Total Saldo: Rp {{ number_format($a->saldo_total, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('anggota_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Simpanan <span class="text-danger">*</span></label>
                            <select name="jenis_simpanan_id" id="select-jenis" class="form-select @error('jenis_simpanan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($jenisList as $j)
                                    <option value="{{ $j->id }}" data-kode="{{ $j->kode_jenis }}" data-nominal="{{ (int)$j->nominal_standar }}" {{ old('jenis_simpanan_id') == $j->id ? 'selected' : '' }}>
                                        {{ $j->nama_simpanan }} {{ $j->nominal_standar > 0 ? '(Standar: Rp '.number_format($j->nominal_standar, 0, ',', '.').')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_simpanan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipe Transaksi <span class="text-danger">*</span></label>
                            <select name="jenis_transaksi" id="select-tipe" class="form-select @error('jenis_transaksi') is-invalid @enderror" required>
                                <option value="setor" {{ old('jenis_transaksi') === 'setor' ? 'selected' : '' }}>Setoran Kas (+)</option>
                                <option value="tarik" {{ old('jenis_transaksi') === 'tarik' ? 'selected' : '' }}>Penarikan Kas (-)</option>
                            </select>
                            @error('jenis_transaksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted" id="tarik-note" style="display:none;">*Penarikan hanya berlaku untuk Simpanan Sukarela.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Nominal (Rp) <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="jumlah" id="input-jumlah" class="form-control form-control-lg @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}" required placeholder="Contoh: 50000">
                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan (Opsional)</label>
                        <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}" placeholder="Contoh: Setoran simpanan wajib bulan November 2026">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.simpanan.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectJenis = document.getElementById('select-jenis');
        const inputJumlah = document.getElementById('input-jumlah');
        const selectTipe = document.getElementById('select-tipe');
        const tarikNote = document.getElementById('tarik-note');

        selectJenis.addEventListener('change', function() {
            const selectedOpt = selectJenis.options[selectJenis.selectedIndex];
            const nominal = selectedOpt.getAttribute('data-nominal');
            if (nominal && parseInt(nominal) > 0 && !inputJumlah.value) {
                inputJumlah.value = nominal;
            }
        });

        selectTipe.addEventListener('change', function() {
            if (selectTipe.value === 'tarik') {
                tarikNote.style.display = 'block';
            } else {
                tarikNote.style.display = 'none';
            }
        });
    });
</script>
@endsection

