<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PengajuanPinjamanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', '');

        $query = PengajuanPinjaman::with('anggota')
            ->orderBy('tgl_pengajuan', 'desc');

        if ($status && in_array($status, ['menunggu', 'disetujui', 'ditolak'])) {
            $query->where('status_pengajuan', $status);
        }

        $pengajuan = $query->paginate(15)->appends($request->query());

        return view('admin.pengajuan.index', compact('pengajuan', 'status'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanPinjaman::with(['anggota', 'petugasVerifikator', 'pinjaman'])
            ->findOrFail($id);

        return view('admin.pengajuan.show', compact('pengajuan'));
    }

    public function setujui(Request $request, $id)
    {
        $request->validate([
            'catatan_verifikasi' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanPinjaman::findOrFail($id);

        if (!$pengajuan->isMenunggu()) {
            return redirect()->route('admin.pengajuan.show', $id)
                ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($pengajuan, $request) {
            // Update status pengajuan
            $pengajuan->update([
                'status_pengajuan'       => 'disetujui',
                'tgl_verifikasi'         => now(),
                'catatan_verifikasi'     => $request->catatan_verifikasi,
                'petugas_verifikator_id' => auth()->id(),
            ]);

            // Kalkulasi finansial pinjaman
            $kalkulasi = Pinjaman::kalkulasi(
                (float) $pengajuan->jumlah_pengajuan,
                (int)   $pengajuan->jangka_waktu_bulan
            );

            // Generate no_pinjaman: PIN-YYYYMM-XXXX
            $prefix     = 'PIN-' . now()->format('Ym') . '-';
            $lastNo     = Pinjaman::where('no_pinjaman', 'like', $prefix . '%')->count() + 1;
            $noPinjaman = $prefix . str_pad($lastNo, 4, '0', STR_PAD_LEFT);

            // Buat record pinjaman
            Pinjaman::create(array_merge($kalkulasi, [
                'no_pinjaman'   => $noPinjaman,
                'pengajuan_id'  => $pengajuan->id,
                'anggota_id'    => $pengajuan->anggota_id,
                'jumlah_pokok'  => $pengajuan->jumlah_pengajuan,
                'lama_bulan'    => $pengajuan->jangka_waktu_bulan,
                'tgl_akad_cair' => now(),
                'status_pinjaman' => 'berjalan',
                'petugas_cair_id' => auth()->id(),
            ]));
        });

        $pinjaman = Pinjaman::where('pengajuan_id', $pengajuan->id)->first();

        return redirect()->route('admin.pinjaman.show', $pinjaman->id)
            ->with('success', 'Pengajuan disetujui dan pinjaman berhasil dibuat.');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_verifikasi' => 'required|string|max:500',
        ]);

        $pengajuan = PengajuanPinjaman::findOrFail($id);

        if (!$pengajuan->isMenunggu()) {
            return redirect()->route('admin.pengajuan.show', $id)
                ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $pengajuan->update([
            'status_pengajuan'       => 'ditolak',
            'tgl_verifikasi'         => now(),
            'catatan_verifikasi'     => $request->catatan_verifikasi,
            'petugas_verifikator_id' => auth()->id(),
        ]);

        return redirect()->route('admin.pengajuan.index')
            ->with('success', 'Pengajuan pinjaman berhasil ditolak.');
    }
}
