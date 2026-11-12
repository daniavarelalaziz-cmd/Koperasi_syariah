<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;

class AngsuranController extends Controller
{
    public function index()
    {
        $anggota = auth()->user()->anggota;

        if (!$anggota) {
            $pinjamanList = collect();
            return view('anggota.angsuran.index', compact('pinjamanList'));
        }

        $pinjamanList = Pinjaman::with('angsuran')
            ->where('anggota_id', $anggota->id)
            ->orderBy('tgl_akad_cair', 'desc')
            ->get();

        return view('anggota.angsuran.index', compact('pinjamanList'));
    }
}
