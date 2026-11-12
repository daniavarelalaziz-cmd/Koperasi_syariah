<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Anggota extends Model
{
    protected $table = 'anggota';

    protected $fillable = [
        'user_id',
        'no_anggota',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'pekerjaan',
        'penghasilan_per_bulan',
        'status_pernikahan',
        'tgl_gabung',
        'status_anggota',
    ];

    protected $casts = [
        'tgl_gabung'           => 'date',
        'penghasilan_per_bulan' => 'decimal:2',
    ];

    // ─── Relasi ──────────────────────────────────────────────

    /** Akun login milik anggota ini */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Semua mutasi simpanan / tabungan anggota ini */
    public function transaksiSimpanan()
    {
        return $this->hasMany(TransaksiSimpanan::class, 'anggota_id');
    }

    /** Semua formulir pengajuan pinjaman anggota ini */
    public function pengajuanPinjaman()
    {
        return $this->hasMany(PengajuanPinjaman::class, 'anggota_id');
    }

    /** Semua pinjaman (kontrak yang sudah ACC) milik anggota ini */
    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class, 'anggota_id');
    }

    // ─── Helper ──────────────────────────────────────────────

    /**
     * Hitung saldo total simpanan anggota.
     * Total Saldo = Σ(Setoran) - Σ(Penarikan)
     */
    public function getSaldoTotalAttribute(): float
    {
        $setor = $this->transaksiSimpanan()
            ->where('jenis_transaksi', 'setor')
            ->sum('jumlah');
        $tarik = $this->transaksiSimpanan()
            ->where('jenis_transaksi', 'tarik')
            ->sum('jumlah');
        return (float) ($setor - $tarik);
    }

    /**
     * Hitung saldo per jenis simpanan.
     * Useful untuk validasi penarikan hanya boleh untuk SUKARELA.
     */
    public function getSaldoPerJenis(int $jenisSimpananId): float
    {
        $setor = $this->transaksiSimpanan()
            ->where('jenis_simpanan_id', $jenisSimpananId)
            ->where('jenis_transaksi', 'setor')
            ->sum('jumlah');
        $tarik = $this->transaksiSimpanan()
            ->where('jenis_simpanan_id', $jenisSimpananId)
            ->where('jenis_transaksi', 'tarik')
            ->sum('jumlah');
        return (float) ($setor - $tarik);
    }

    /** Pinjaman yang sedang berjalan (belum lunas) */
    public function pinjamanAktif()
    {
        return $this->pinjaman()->where('status_pinjaman', 'berjalan');
    }

    public function isAktif(): bool
    {
        return $this->status_anggota === 'aktif';
    }
}
