<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPinjaman extends Model
{
    protected $table = 'pengajuan_pinjaman';

    protected $fillable = [
        'no_pengajuan',
        'anggota_id',
        'jumlah_pengajuan',
        'jangka_waktu_bulan',
        'tujuan_pinjaman',
        'berkas_ktp',
        'berkas_kk',
        'tgl_pengajuan',
        'status_pengajuan',
        'tgl_verifikasi',
        'catatan_verifikasi',
        'petugas_verifikator_id',
    ];

    protected $casts = [
        'tgl_pengajuan'   => 'date',
        'tgl_verifikasi'  => 'datetime',
        'jumlah_pengajuan' => 'decimal:2',
    ];

    // ─── Relasi ──────────────────────────────────────────────

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function petugasVerifikator()
    {
        return $this->belongsTo(User::class, 'petugas_verifikator_id');
    }

    /** Kontrak pinjaman yang terbentuk setelah pengajuan ini disetujui */
    public function pinjaman()
    {
        return $this->hasOne(Pinjaman::class, 'pengajuan_id');
    }

    // ─── Helper ──────────────────────────────────────────────

    public function isMenunggu(): bool
    {
        return $this->status_pengajuan === 'menunggu';
    }

    public function isDiSetujui(): bool
    {
        return $this->status_pengajuan === 'disetujui';
    }

    public function isDitolak(): bool
    {
        return $this->status_pengajuan === 'ditolak';
    }
}
