<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\TransaksiSimpanan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        return view('pimpinan.laporan.index');
    }

    public function simpanan(Request $request)
    {
        $query = TransaksiSimpanan::with(['anggota', 'jenisSimpanan']);

        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        if ($bulan && $tahun) {
            $query->whereMonth('tgl_transaksi', $bulan)->whereYear('tgl_transaksi', $tahun);
        }

        $transaksi = $query->orderBy('tgl_transaksi', 'desc')->get();
        $totalSetor = $transaksi->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = $transaksi->where('jenis_transaksi', 'tarik')->sum('jumlah');

        return view('pimpinan.laporan.simpanan', compact('transaksi', 'bulan', 'tahun', 'totalSetor', 'totalTarik'));
    }

    public function pinjaman(Request $request)
    {
        $query = Pinjaman::with(['anggota', 'angsuran']);

        $tahun = $request->get('tahun', date('Y'));
        $status = $request->get('status');

        if ($tahun) {
            $query->whereYear('tgl_akad_cair', $tahun);
        }
        if ($status) {
            $query->where('status_pinjaman', $status);
        }

        $pinjaman = $query->orderBy('tgl_akad_cair', 'desc')->get();

        return view('pimpinan.laporan.pinjaman', compact('pinjaman', 'tahun', 'status'));
    }

    public function angsuran(Request $request)
    {
        $query = Angsuran::with(['pinjaman.anggota', 'petugas']);

        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        if ($bulan && $tahun) {
            $query->whereMonth('tgl_bayar', $bulan)->whereYear('tgl_bayar', $tahun);
        }

        $angsuran = $query->orderBy('tgl_bayar', 'desc')->get();

        return view('pimpinan.laporan.angsuran', compact('angsuran', 'bulan', 'tahun'));
    }

    public function anggota()
    {
        $anggota = Anggota::with('transaksiSimpanan')->orderBy('nama_lengkap')->get();
        return view('pimpinan.laporan.anggota', compact('anggota'));
    }
}

