<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Angsuran;
use App\Models\JenisSimpanan;
use App\Models\Pinjaman;
use App\Models\Setting;
use App\Models\TransaksiSimpanan;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BayarController extends Controller
{
    /**
     * Tampilkan halaman bayar angsuran via QRIS
     * GET /anggota/bayar/angsuran/{pinjaman_id}
     */
    public function angsuran($pinjamanId)
    {
        $anggota  = auth()->user()->anggota;
        if (!$anggota) {
            return redirect()->route('anggota.dashboard');
        }

        $pinjaman = Pinjaman::where('id', $pinjamanId)
            ->where('anggota_id', $anggota->id)
            ->where('status_pinjaman', 'berjalan')
            ->firstOrFail();

        $angsuranKe = $pinjaman->getAngsuranBerikutnyaKe();

        if ($angsuranKe > $pinjaman->lama_bulan) {
            return redirect()->route('anggota.angsuran.index')
                ->with('info', 'Semua angsuran sudah lunas!');
        }

        $nominal    = (int) $pinjaman->total_angsuran_per_bulan;
        $keterangan = 'Angsuran Ke-' . $angsuranKe . ' Pinjaman ' . $pinjaman->no_pinjaman;
        $tglJatuhTempo = $pinjaman->tgl_akad_cair
            ? \Carbon\Carbon::parse($pinjaman->tgl_akad_cair)->addMonths($angsuranKe)
            : now()->addMonth();

        $qrisString = Setting::get('qris_string', '');
        $qrsvg      = $this->generateQr($qrisString, $nominal);

        return view('anggota.bayar.angsuran', compact(
            'pinjaman', 'angsuranKe', 'tglJatuhTempo', 'nominal', 'keterangan', 'qrsvg', 'qrisString'
        ));
    }

    /**
     * Tampilkan halaman bayar simpanan via QRIS
     * GET /anggota/bayar/simpanan/{jenis_simpanan_id}
     */
    public function simpanan($jenisSimpananId)
    {
        $anggota      = auth()->user()->anggota;
        $jenisSimpanan = JenisSimpanan::findOrFail($jenisSimpananId);

        $nominal    = $jenisSimpanan->nominal_standar > 0 ? $jenisSimpanan->nominal_standar : 0;
        $keterangan = 'Pembayaran ' . $jenisSimpanan->nama_simpanan . ' - ' . ($anggota->nama_anggota ?? auth()->user()->username);
        $qrisString = Setting::get('qris_string', '');
        $qrsvg      = $this->generateQr($qrisString, $nominal);

        return view('anggota.bayar.simpanan', compact(
            'anggota', 'jenisSimpanan', 'nominal', 'keterangan', 'qrsvg', 'qrisString'
        ));
    }

    /**
     * Generate QR SVG dari QRIS string asli
     */
    private function generateQr(string $qrisString, int $nominal = 0): ?string
    {
        $qrisString = trim($qrisString);
        if (empty($qrisString)) {
            return null;
        }

        try {
            // Gunakan string QRIS asli tanpa merusak struktur dan CRC dari Bank/PJP
            return (string) QrCode::format('svg')
                ->size(280)
                ->errorCorrection('M')
                ->generate($qrisString);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Inject nominal ke QRIS string (EMVCo Field 54)
     */
    private function injectNominal(string $qrisString, int $nominal): string
    {
        $nominalStr = (string) $nominal;
        $field54    = '54' . str_pad(strlen($nominalStr), 2, '0', STR_PAD_LEFT) . $nominalStr;

        $qrisString = preg_replace('/54\d{2}\d+/', '', $qrisString);
        $qrisString = preg_replace('/6304[A-F0-9]{4}$/i', '', $qrisString);

        if (($pos = strpos($qrisString, '58')) !== false) {
            $qrisString = substr($qrisString, 0, $pos) . $field54 . substr($qrisString, $pos);
        } else {
            $qrisString .= $field54;
        }

        $qrisString .= '6304';
        $qrisString .= strtoupper($this->crc16($qrisString));

        return $qrisString;
    }

    private function crc16(string $data): string
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }
        return str_pad(dechex($crc), 4, '0', STR_PAD_LEFT);
    }
}

