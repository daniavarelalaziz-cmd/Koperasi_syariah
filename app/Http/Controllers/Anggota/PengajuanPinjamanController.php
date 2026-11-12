<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengajuanPinjamanController extends Controller
{
    public function index()
    {
        $anggota  = auth()->user()->anggota;
        $pengajuan = PengajuanPinjaman::where('anggota_id', $anggota->id)
            ->orderBy('tgl_pengajuan', 'desc')
            ->get();

        return view('anggota.pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        return view('anggota.pengajuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah_pengajuan'   => 'required|numeric|min:100000',
            'jangka_waktu_bulan' => 'required|integer|min:1|max:60',
            'tujuan_pinjaman'    => 'required|string|max:500',
            'berkas_ktp'         => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'berkas_kk'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $anggota = auth()->user()->anggota;

        // Cek apakah ada pengajuan menunggu
        $adaPengajuanMenunggu = PengajuanPinjaman::where('anggota_id', $anggota->id)
            ->where('status_pengajuan', 'menunggu')
            ->exists();

        if ($adaPengajuanMenunggu) {
            return back()->with('error', 'Anda masih memiliki pengajuan pinjaman yang sedang menunggu verifikasi.')
                         ->withInput();
        }

        // Cek apakah ada pinjaman berjalan
        $adaPinjamanBerjalan = $anggota->pinjamanAktif()->exists();

        if ($adaPinjamanBerjalan) {
            return back()->with('error', 'Anda masih memiliki pinjaman yang sedang berjalan.')
                         ->withInput();
        }

        // Upload berkas
        $pathKtp = $request->file('berkas_ktp')->store('berkas', 'public');
        $pathKk  = $request->file('berkas_kk')->store('berkas', 'public');

        // Generate no_pengajuan: PJN-YYYYMM-XXXX
        $prefix      = 'PJN-' . now()->format('Ym') . '-';
        $lastNo      = PengajuanPinjaman::where('no_pengajuan', 'like', $prefix . '%')->count() + 1;
        $noPengajuan = $prefix . str_pad($lastNo, 4, '0', STR_PAD_LEFT);

        PengajuanPinjaman::create([
            'no_pengajuan'       => $noPengajuan,
            'anggota_id'         => $anggota->id,
            'jumlah_pengajuan'   => $request->jumlah_pengajuan,
            'jangka_waktu_bulan' => $request->jangka_waktu_bulan,
            'tujuan_pinjaman'    => $request->tujuan_pinjaman,
            'berkas_ktp'         => $pathKtp,
            'berkas_kk'          => $pathKk,
            'tgl_pengajuan'      => now(),
            'status_pengajuan'   => 'menunggu',
        ]);

        return redirect()->route('anggota.pengajuan.index')
            ->with('success', 'Pengajuan pinjaman berhasil dikirim. Nomor: ' . $noPengajuan);
    }
}
