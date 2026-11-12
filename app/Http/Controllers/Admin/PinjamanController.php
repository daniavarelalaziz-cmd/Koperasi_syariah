<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $status = $request->query('status', '');

        $query = Pinjaman::with('anggota')
            ->orderBy('tgl_akad_cair', 'desc');

        if ($status && in_array($status, ['berjalan', 'lunas', 'macet'])) {
            $query->where('status_pinjaman', $status);
        }

        if ($search) {
            $query->whereHas('anggota', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_anggota', 'like', "%{$search}%");
            });
        }

        $pinjaman = $query->paginate(15)->appends($request->query());

        return view('admin.pinjaman.index', compact('pinjaman', 'search', 'status'));
    }

    public function show($id)
    {
        $pinjaman = Pinjaman::with(['anggota', 'pengajuan', 'angsuran', 'petugasCair'])
            ->findOrFail($id);

        // Buat jadwal angsuran (1 s/d lama_bulan)
        $angsuranSudahBayar = $pinjaman->angsuran->keyBy('angsuran_ke');
        $jadwal = [];
        for ($ke = 1; $ke <= $pinjaman->lama_bulan; $ke++) {
            $jadwal[] = [
                'ke'           => $ke,
                'sudah_bayar'  => $angsuranSudahBayar->has($ke),
                'data'         => $angsuranSudahBayar->get($ke),
            ];
        }

        $angsuranBerikutnyaKe = $pinjaman->getAngsuranBerikutnyaKe();

        return view('admin.pinjaman.show', compact('pinjaman', 'jadwal', 'angsuranBerikutnyaKe'));
    }
}
