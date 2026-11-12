<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $table = 'pinjaman';

    protected $fillable = [
        'no_pinjaman',
        'pengajuan_id',
        'anggota_id',
        'jumlah_pokok',
        'nisbah_bagi_hasil',
        'total_bagi_hasil',
        'total_tagihan',
        'lama_bulan',
        'angsuran_pokok_per_bulan',
        'bagi_hasil_per_bulan',
        'total_angsuran_per_bulan',
        'tgl_akad_cair',
        'status_pinjaman',
        'tgl_lunas',
        'petugas_cair_id',
    ];

    protected $casts = [
        'tgl_akad_cair'              => 'date',
        'tgl_lunas'                  => 'date',
        'jumlah_pokok'               => 'decimal:2',
        'nisbah_bagi_hasil'          => 'decimal:2',
        'total_bagi_hasil'           => 'decimal:2',
        'total_tagihan'              => 'decimal:2',
        'angsuran_pokok_per_bulan'   => 'decimal:2',
        'bagi_hasil_per_bulan'       => 'decimal:2',
        'total_angsuran_per_bulan'   => 'decimal:2',
    ];

    // ─── Relasi ──────────────────────────────────────────────

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPinjaman::class, 'pengajuan_id');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function petugasCair()
    {
        return $this->belongsTo(User::class, 'petugas_cair_id');
    }

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class, 'pinjaman_id')->orderBy('angsuran_ke');
    }

    // ─── Helper ──────────────────────────────────────────────

    /**
     * Hitung angsuran berikutnya yang harus dibayar.
     * Angsuran ke-(jumlah_yang_sudah_lunas + 1)
     */
    public function getAngsuranBerikutnyaKe(): int
    {
        return $this->angsuran()->count() + 1;
    }

    /**
     * Hitung sisa pokok yang belum terbayar.
     */
    public function getSisaPokokAttribute(): float
    {
        $sudahBayarPokok = $this->angsuran()->sum('bayar_pokok');
        return (float) ($this->jumlah_pokok - $sudahBayarPokok);
    }

    public function isBerjalan(): bool
    {
        return $this->status_pinjaman === 'berjalan';
    }

    public function isLunas(): bool
    {
        return $this->status_pinjaman === 'lunas';
    }

    public function isMacet(): bool
    {
        return $this->status_pinjaman === 'macet';
    }

    /**
     * Factory method: kalkulasi otomatis semua kolom finansial
     * dari jumlah_pokok, lama_bulan, dan nisbah_bagi_hasil.
     * Gunakan sebelum menyimpan record pinjaman baru.
     *
     * Contoh: $data = Pinjaman::kalkulasi(1000000, 10, 2.00);
     */
    public static function kalkulasi(float $pokokPinjaman, int $lamaBulan, float $nisbah = 2.00): array
    {
        $baghasilPerBulan       = $pokokPinjaman * ($nisbah / 100);
        $totalBaghasil          = $baghasilPerBulan * $lamaBulan;
        $totalTagihan           = $pokokPinjaman + $totalBaghasil;
        $angsuranPokokPerBulan  = $pokokPinjaman / $lamaBulan;
        $totalAngsuranPerBulan  = $angsuranPokokPerBulan + $baghasilPerBulan;

        return [
            'nisbah_bagi_hasil'         => $nisbah,
            'bagi_hasil_per_bulan'      => round($baghasilPerBulan, 2),
            'total_bagi_hasil'          => round($totalBaghasil, 2),
            'total_tagihan'             => round($totalTagihan, 2),
            'angsuran_pokok_per_bulan'  => round($angsuranPokokPerBulan, 2),
            'total_angsuran_per_bulan'  => round($totalAngsuranPerBulan, 2),
        ];
    }
}
