<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Angsuran;
use App\Models\JenisSimpanan;
use App\Models\Pinjaman;
use App\Models\TransaksiSimpanan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function simpanan(Request $request)
    {
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        $transaksi = TransaksiSimpanan::with(['anggota', 'jenisSimpanan'])
            ->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->orderBy('tgl_transaksi', 'desc')
            ->get();

        $totalMasuk  = $transaksi->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalKeluar = $transaksi->where('jenis_transaksi', 'tarik')->sum('jumlah');

        return view('admin.laporan.simpanan', compact('transaksi', 'bulan', 'tahun', 'totalMasuk', 'totalKeluar'));
    }

    public function pinjaman(Request $request)
    {
        $bulan  = $request->query('bulan', '');
        $tahun  = $request->query('tahun', now()->year);
        $status = $request->query('status', '');

        $query = Pinjaman::with('anggota')->orderBy('tgl_akad_cair', 'desc');

        if ($bulan) {
            $query->whereMonth('tgl_akad_cair', $bulan);
        }
        if ($tahun) {
            $query->whereYear('tgl_akad_cair', $tahun);
        }
        if ($status && in_array($status, ['berjalan', 'lunas', 'macet'])) {
            $query->where('status_pinjaman', $status);
        }

        $pinjaman = $query->get();

        return view('admin.laporan.pinjaman', compact('pinjaman', 'bulan', 'tahun', 'status'));
    }

    public function angsuran(Request $request)
    {
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        $angsuran = Angsuran::with(['pinjaman.anggota', 'petugas'])
            ->whereMonth('tgl_bayar', $bulan)
            ->whereYear('tgl_bayar', $tahun)
            ->orderBy('tgl_bayar', 'desc')
            ->get();

        $totalAngsuran   = $angsuran->sum('total_bayar');
        $totalPokok      = $angsuran->sum('bayar_pokok');
        $totalBagiHasil  = $angsuran->sum('bayar_bagi_hasil');

        return view('admin.laporan.angsuran', compact('angsuran', 'bulan', 'tahun', 'totalAngsuran', 'totalPokok', 'totalBagiHasil'));
    }

    public function anggota()
    {
        $jenisSimpanan = JenisSimpanan::all();

        $anggota = Anggota::with('transaksiSimpanan')->where('status_anggota', 'aktif')->get();

        $data = $anggota->map(function ($a) use ($jenisSimpanan) {
            $saldo = [];
            foreach ($jenisSimpanan as $js) {
                $saldo[$js->kode_jenis] = $a->getSaldoPerJenis($js->id);
            }
            $saldo['total'] = $a->saldo_total;
            return ['anggota' => $a, 'saldo' => $saldo];
        });

        return view('admin.laporan.anggota', compact('data', 'jenisSimpanan'));
    }

    public function cetakSimpanan(Request $request)
    {
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        $transaksi = TransaksiSimpanan::with(['anggota', 'jenisSimpanan'])
            ->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->orderBy('tgl_transaksi', 'asc')
            ->get();

        $totalMasuk  = $transaksi->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalKeluar = $transaksi->where('jenis_transaksi', 'tarik')->sum('jumlah');

        return view('admin.laporan.cetak_simpanan', compact('transaksi', 'bulan', 'tahun', 'totalMasuk', 'totalKeluar'));
    }

    public function cetakAngsuran(Request $request)
    {
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        $angsuran = Angsuran::with(['pinjaman.anggota', 'petugas'])
            ->whereMonth('tgl_bayar', $bulan)
            ->whereYear('tgl_bayar', $tahun)
            ->orderBy('tgl_bayar', 'asc')
            ->get();

        $totalAngsuran  = $angsuran->sum('total_bayar');
        $totalPokok     = $angsuran->sum('bayar_pokok');
        $totalBagiHasil = $angsuran->sum('bayar_bagi_hasil');

        return view('admin.laporan.cetak_angsuran', compact('angsuran', 'bulan', 'tahun', 'totalAngsuran', 'totalPokok', 'totalBagiHasil'));
    }
}
