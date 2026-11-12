<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\JenisSimpanan;
use App\Models\TransaksiSimpanan;
use Illuminate\Http\Request;

class SimpananController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $anggota = $user->anggota;

        if (!$anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'Profil anggota tidak ditemukan.');
        }

        $transaksi = TransaksiSimpanan::with('jenisSimpanan')
            ->where('anggota_id', $anggota->id)
            ->orderBy('tgl_transaksi', 'desc')
            ->paginate(15);

        $jenisList = JenisSimpanan::all();
        $saldoPerJenis = [];
        foreach ($jenisList as $j) {
            $saldoPerJenis[] = [
                'kode'   => $j->kode_jenis,
                'nama'   => $j->nama_simpanan,
                'saldo'  => $anggota->getSaldoPerJenis($j->id),
            ];
        }

        return view('anggota.simpanan.index', compact('anggota', 'transaksi', 'saldoPerJenis'));
    }
}

