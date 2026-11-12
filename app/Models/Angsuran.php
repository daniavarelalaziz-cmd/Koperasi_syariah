<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    protected $table = 'angsuran';

    protected $fillable = [
        'no_bukti_angsuran',
        'pinjaman_id',
        'angsuran_ke',
        'tgl_bayar',
        'bayar_pokok',
        'bayar_bagi_hasil',
        'total_bayar',
        'keterangan',
        'petugas_id',
    ];

    protected $casts = [
        'tgl_bayar'        => 'datetime',
        'bayar_pokok'      => 'decimal:2',
        'bayar_bagi_hasil' => 'decimal:2',
        'total_bayar'      => 'decimal:2',
    ];

    // ─── Relasi ──────────────────────────────────────────────

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'pinjaman_id');
    }

    /** Petugas yang menerima pembayaran angsuran ini */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // ─── Helper ──────────────────────────────────────────────

    /**
     * Shortcut: dapatkan data anggota melalui relasi pinjaman
     * (membuktikan 3NF - tidak perlu menyimpan anggota_id di sini)
     */
    public function getAnggotaAttribute()
    {
        return $this->pinjaman->anggota;
    }
}
