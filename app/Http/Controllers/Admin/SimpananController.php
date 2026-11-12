<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\TransaksiSimpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimpananController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiSimpanan::with(['anggota', 'jenisSimpanan', 'petugas']);

        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }
        if ($request->filled('jenis_simpanan_id')) {
            $query->where('jenis_simpanan_id', $request->jenis_simpanan_id);
        }
        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }
        if ($request->filled('tgl_mulai')) {
            $query->whereDate('tgl_transaksi', '>=', $request->tgl_mulai);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tgl_transaksi', '<=', $request->tgl_akhir);
        }

        $transaksi = $query->orderBy('tgl_transaksi', 'desc')->paginate(20)->withQueryString();
        $anggotaList = Anggota::where('status_anggota', 'aktif')->orderBy('nama_lengkap')->get();
        $jenisList = JenisSimpanan::all();

        return view('admin.simpanan.index', compact('transaksi', 'anggotaList', 'jenisList'));
    }

    public function create()
    {
        $anggotaList = Anggota::where('status_anggota', 'aktif')->orderBy('nama_lengkap')->get();
        $jenisList = JenisSimpanan::all();
        return view('admin.simpanan.create', compact('anggotaList', 'jenisList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id'        => 'required|exists:anggota,id',
            'jenis_simpanan_id' => 'required|exists:jenis_simpanan,id',
            'jenis_transaksi'   => 'required|in:setor,tarik',
            'jumlah'            => 'required|numeric|min:1000',
            'keterangan'        => 'nullable|string|max:255',
        ]);

        $anggota = Anggota::findOrFail($request->anggota_id);
        $jenis = JenisSimpanan::findOrFail($request->jenis_simpanan_id);

        // Aturan Koperasi: Penarikan hanya boleh untuk simpanan sukarela / tabungan
        if ($request->jenis_transaksi === 'tarik') {
            if ($jenis->kode_jenis !== JenisSimpanan::SUKARELA) {
                return back()->withInput()->with('error', 'Penarikan hanya diperbolehkan untuk Simpanan Sukarela / Tabungan!');
            }

            $saldoSukarela = $anggota->getSaldoPerJenis($jenis->id);
            if ($request->jumlah > $saldoSukarela) {
                return back()->withInput()->with('error', 'Saldo simpanan sukarela tidak mencukupi! Saldo saat ini: Rp ' . number_format($saldoSukarela, 0, ',', '.'));
            }
        }

        $noTrx = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        $trx = TransaksiSimpanan::create([
            'no_transaksi'      => $noTrx,
            'anggota_id'        => $anggota->id,
            'jenis_simpanan_id' => $jenis->id,
            'jenis_transaksi'   => $request->jenis_transaksi,
            'jumlah'            => $request->jumlah,
            'tgl_transaksi'     => now(),
            'keterangan'        => $request->keterangan ?: ($request->jenis_transaksi === 'setor' ? "Setoran {$jenis->nama_simpanan}" : "Penarikan {$jenis->nama_simpanan}"),
            'petugas_id'        => auth()->id(),
        ]);

        return redirect()->route('admin.simpanan.show', $trx->id)
            ->with('success', 'Transaksi simpanan berhasil dicatat!');
    }

    public function show($id)
    {
        $trx = TransaksiSimpanan::with(['anggota', 'jenisSimpanan', 'petugas'])->findOrFail($id);
        return view('admin.simpanan.show', compact('trx'));
    }
}

