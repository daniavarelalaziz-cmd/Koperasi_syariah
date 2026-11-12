<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    // ─── Relasi ──────────────────────────────────────────────

    /** Profil anggota jika user adalah role anggota */
    public function anggota()
    {
        return $this->hasOne(Anggota::class, 'user_id');
    }

    /** Transaksi simpanan yang dicatat oleh user ini (sebagai petugas) */
    public function transaksiSimpananDicatat()
    {
        return $this->hasMany(TransaksiSimpanan::class, 'petugas_id');
    }

    /** Pengajuan pinjaman yang diverifikasi oleh user ini */
    public function pengajuanDiverifikasi()
    {
        return $this->hasMany(PengajuanPinjaman::class, 'petugas_verifikator_id');
    }

    /** Pinjaman yang dicairkan oleh user ini */
    public function pinjamanDicairkan()
    {
        return $this->hasMany(Pinjaman::class, 'petugas_cair_id');
    }

    /** Angsuran yang diterima oleh user ini (sebagai petugas) */
    public function angsuranDiterima()
    {
        return $this->hasMany(Angsuran::class, 'petugas_id');
    }

    // ─── Helper ──────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAnggota(): bool
    {
        return $this->role === 'anggota';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }
}
