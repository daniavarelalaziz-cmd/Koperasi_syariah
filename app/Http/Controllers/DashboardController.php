<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\TransaksiSimpanan;
use App\Models\PengajuanPinjaman;
use App\Models\Pinjaman;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Dashboard Admin.
     */
    public function admin()
    {
        $totalAnggota      = Anggota::count();
        $anggotaAktif      = Anggota::where('status_anggota', 'aktif')->count();
        $totalSimpanan     = TransaksiSimpanan::where('jenis_transaksi', 'setor')->sum('jumlah')
                           - TransaksiSimpanan::where('jenis_transaksi', 'tarik')->sum('jumlah');
        $pengajuanPending  = PengajuanPinjaman::where('status_pengajuan', 'menunggu')->count();
        $pinjamanBerjalan  = Pinjaman::where('status_pinjaman', 'berjalan')->count();

        return view('admin.dashboard', compact(
            'totalAnggota',
            'anggotaAktif',
            'totalSimpanan',
            'pengajuanPending',
            'pinjamanBerjalan'
        ));
    }

    /**
     * Dashboard Pimpinan.
     */
    public function pimpinan()
    {
        $totalAnggota     = Anggota::count();
        $totalSimpanan    = TransaksiSimpanan::where('jenis_transaksi', 'setor')->sum('jumlah')
                          - TransaksiSimpanan::where('jenis_transaksi', 'tarik')->sum('jumlah');

        $pinjamanBerjalanList = Pinjaman::with('angsuran')->where('status_pinjaman', 'berjalan')->get();
        $totalPinjaman    = $pinjamanBerjalanList->sum(function ($p) {
            return $p->sisa_pokok;
        });
        $pinjamanBerjalan = $pinjamanBerjalanList->count();

        return view('pimpinan.dashboard', compact(
            'totalAnggota',
            'totalSimpanan',
            'totalPinjaman',
            'pinjamanBerjalan'
        ));
    }

    /**
     * Dashboard Anggota.
     */
    public function anggota()
    {
        $user    = auth()->user();
        $anggota = $user->anggota;

        $saldoTotal    = 0;
        $saldoPerJenis = [];
        $pinjamanAktif = null;

        if ($anggota) {
            $saldoTotal    = $anggota->saldo_total;
            $pinjamanAktif = $anggota->pinjamanAktif()->with('angsuran')->first();

            $jenisList = \App\Models\JenisSimpanan::all();
            foreach ($jenisList as $j) {
                $saldoPerJenis[] = [
                    'kode'   => $j->kode_jenis,
                    'nama'   => $j->nama_simpanan,
                    'saldo'  => $anggota->getSaldoPerJenis($j->id),
                ];
            }
        }

        return view('anggota.dashboard', compact('user', 'anggota', 'saldoTotal', 'saldoPerJenis', 'pinjamanAktif'));
    }
}
