<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSimpanan extends Model
{
    protected $table = 'jenis_simpanan';

    protected $fillable = [
        'kode_jenis',
        'nama_simpanan',
        'nominal_standar',
        'keterangan',
    ];

    protected $casts = [
        'nominal_standar' => 'decimal:2',
    ];

    // Konstanta kode jenis untuk kemudahan referensi di Controller/Seeder
    const POKOK    = 'POKOK';
    const WAJIB    = 'WAJIB';
    const SUKARELA = 'SUKARELA';

    // ─── Relasi ──────────────────────────────────────────────

    public function transaksiSimpanan()
    {
        return $this->hasMany(TransaksiSimpanan::class, 'jenis_simpanan_id');
    }

    // ─── Helper ──────────────────────────────────────────────

    public function isSukarela(): bool
    {
        return $this->kode_jenis === self::SUKARELA;
    }
}
