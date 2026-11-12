<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrisController extends Controller
{
    /**
     * Generate QR code SVG dari QRIS string yang tersimpan
     * dengan nominal yang bisa dimodifikasi (QRIS Dinamis sederhana)
     *
     * GET /admin/qris/preview?nominal=50000
     */
    public function preview(Request $request)
    {
        $qrisString = trim(Setting::get('qris_string', ''));

        if (empty($qrisString)) {
            return response()->json(['error' => 'QRIS string belum dikonfigurasi'], 400);
        }

        $qrSvg = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('M')
            ->generate($qrisString);

        return response($qrSvg)->header('Content-Type', 'image/svg+xml');
    }

    /**
     * Inject nominal ke QRIS string EMVCo standar
     * Field 54 = Transaction Amount
     */
    private function injectNominal(string $qrisString, int $nominal): string
    {
        $nominalStr = (string) $nominal;
        $field54 = '54' . str_pad(strlen($nominalStr), 2, '0', STR_PAD_LEFT) . $nominalStr;

        // Hapus field 54 yang sudah ada (jika ada)
        $qrisString = preg_replace('/54\d{2}\d+/', '', $qrisString);

        // Hapus CRC lama (field 6304...)
        $qrisString = preg_replace('/6304[A-F0-9]{4}$/i', '', $qrisString);

        // Inject field 54 sebelum field 58 (Country Code)
        if (strpos($qrisString, '58') !== false) {
            $pos = strpos($qrisString, '58');
            $qrisString = substr($qrisString, 0, $pos) . $field54 . substr($qrisString, $pos);
        } else {
            $qrisString .= $field54;
        }

        // Tambahkan field 63 (CRC) di akhir
        $qrisString .= '6304';
        $crc = $this->crc16($qrisString);
        $qrisString .= strtoupper($crc);

        return $qrisString;
    }

    /**
     * Hitung CRC16 CCITT (standar EMVCo QRIS)
     */
    private function crc16(string $data): string
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc <<= 1;
                }
                $crc &= 0xFFFF;
            }
        }
        return str_pad(dechex($crc), 4, '0', STR_PAD_LEFT);
    }
}

