<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use Illuminate\Http\Request;

class AngsuranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pinjaman_id' => 'required|exists:pinjaman,id',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        $pinjaman = Pinjaman::with('angsuran')->findOrFail($request->pinjaman_id);

        if (!$pinjaman->isBerjalan()) {
            return redirect()->route('admin.pinjaman.show', $pinjaman->id)
                ->with('error', 'Pinjaman ini tidak dalam status berjalan.');
        }

        $angsuranKe = $pinjaman->angsuran->count() + 1;

        if ($angsuranKe > $pinjaman->lama_bulan) {
            return redirect()->route('admin.pinjaman.show', $pinjaman->id)
                ->with('error', 'Semua angsuran sudah lunas.');
        }

        // Generate no_bukti_angsuran: ANG-YYYYMMDD-XXXX
        $prefix      = 'ANG-' . now()->format('Ymd') . '-';
        $lastNo      = Angsuran::where('no_bukti_angsuran', 'like', $prefix . '%')->count() + 1;
        $noBukti     = $prefix . str_pad($lastNo, 4, '0', STR_PAD_LEFT);

        $angsuran = Angsuran::create([
            'no_bukti_angsuran' => $noBukti,
            'pinjaman_id'       => $pinjaman->id,
            'angsuran_ke'       => $angsuranKe,
            'tgl_bayar'         => now(),
            'bayar_pokok'       => $pinjaman->angsuran_pokok_per_bulan,
            'bayar_bagi_hasil'  => $pinjaman->bagi_hasil_per_bulan,
            'total_bayar'       => $pinjaman->total_angsuran_per_bulan,
            'keterangan'        => $request->keterangan,
            'petugas_id'        => auth()->id(),
        ]);

        // Jika ini adalah angsuran terakhir, ubah status pinjaman menjadi lunas
        if ($angsuranKe == $pinjaman->lama_bulan) {
            $pinjaman->update([
                'status_pinjaman' => 'lunas',
                'tgl_lunas'       => now(),
            ]);
        }

        return redirect()->route('admin.angsuran.show', $angsuran->id)
            ->with('success', "Angsuran ke-{$angsuranKe} berhasil dicatat.");
    }

    public function show($id)
    {
        $angsuran = Angsuran::with(['pinjaman.anggota', 'petugas'])->findOrFail($id);

        return view('admin.angsuran.show', compact('angsuran'));
    }
}
