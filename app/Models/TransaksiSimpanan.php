<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiSimpanan extends Model
{
    protected $table = 'transaksi_simpanan';

    protected $fillable = [
        'no_transaksi',
        'anggota_id',
        'jenis_simpanan_id',
        'jenis_transaksi',
        'jumlah',
        'tgl_transaksi',
        'keterangan',
        'petugas_id',
    ];

    protected $casts = [
        'tgl_transaksi' => 'datetime',
        'jumlah'        => 'decimal:2',
    ];

    // ─── Relasi ──────────────────────────────────────────────

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function jenisSimpanan()
    {
        return $this->belongsTo(JenisSimpanan::class, 'jenis_simpanan_id');
    }

    /** Petugas yang mencatat transaksi ini */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // ─── Helper ──────────────────────────────────────────────

    public function isSetor(): bool
    {
        return $this->jenis_transaksi === 'setor';
    }

    public function isTarik(): bool
    {
        return $this->jenis_transaksi === 'tarik';
    }
}
