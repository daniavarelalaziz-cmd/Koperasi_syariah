<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use App\Models\JenisSimpanan;
use App\Models\TransaksiSimpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $query = Anggota::with('user');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($b) use ($q) {
                $b->where('nama_lengkap', 'like', "%{$q}%")
                  ->orWhere('no_anggota', 'like', "%{$q}%")
                  ->orWhere('nik', 'like', "%{$q}%")
                  ->orWhere('no_hp', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status_anggota', $request->status);
        }

        $anggota = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'         => 'required|string|max:100',
            'nik'                  => 'required|string|size:16|unique:anggota,nik',
            'jenis_kelamin'        => 'required|in:L,P',
            'no_hp'                => 'required|string|max:15',
            'alamat'               => 'required|string',
            'pekerjaan'            => 'required|string|max:100',
            'penghasilan_per_bulan'=> 'required|numeric|min:0',
            'status_pernikahan'    => 'required|in:Belum Menikah,Menikah,Cerai',
            'username'             => 'required|string|max:50|unique:users,username',
            'password'             => 'required|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat User Login Anggota
            $user = User::create([
                'username'     => $request->username,
                'password'     => Hash::make($request->password),
                'role'         => 'anggota',
                'status_aktif' => true,
            ]);

            // 2. Generate No Anggota unik format ANG-YYYYMM-XXXX
            $prefix = 'ANG-' . date('Ym') . '-';
            $lastAnggota = Anggota::where('no_anggota', 'like', "{$prefix}%")
                ->orderBy('no_anggota', 'desc')
                ->first();

            $nextNumber = 1;
            if ($lastAnggota) {
                $lastSequence = (int) substr($lastAnggota->no_anggota, -4);
                $nextNumber = $lastSequence + 1;
            }
            $noAnggota = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // 3. Simpan Profil Anggota
            $anggota = Anggota::create([
                'user_id'              => $user->id,
                'no_anggota'           => $noAnggota,
                'nik'                  => $request->nik,
                'nama_lengkap'         => $request->nama_lengkap,
                'jenis_kelamin'        => $request->jenis_kelamin,
                'no_hp'                => $request->no_hp,
                'alamat'               => $request->alamat,
                'pekerjaan'            => $request->pekerjaan,
                'penghasilan_per_bulan'=> $request->penghasilan_per_bulan,
                'status_pernikahan'    => $request->status_pernikahan,
                'tgl_gabung'           => now()->toDateString(),
                'status_anggota'       => 'aktif',
            ]);

            // 4. Catat Setoran Simpanan Pokok Wajib Pendaftaran (Rp 20.000)
            $jsPokok = JenisSimpanan::where('kode_jenis', JenisSimpanan::POKOK)->first();
            if ($jsPokok) {
                TransaksiSimpanan::create([
                    'no_transaksi'      => 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5)),
                    'anggota_id'        => $anggota->id,
                    'jenis_simpanan_id' => $jsPokok->id,
                    'jenis_transaksi'   => 'setor',
                    'jumlah'            => $jsPokok->nominal_standar > 0 ? $jsPokok->nominal_standar : 20000.00,
                    'tgl_transaksi'     => now(),
                    'keterangan'        => 'Setoran Simpanan Pokok Pendaftaran Anggota Baru',
                    'petugas_id'        => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.anggota.show', $anggota->id)
                ->with('success', "Anggota {$anggota->nama_lengkap} ({$anggota->no_anggota}) berhasil didaftarkan beserta simpanan pokok awal!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mendaftarkan anggota: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $anggota = Anggota::with(['user', 'transaksiSimpanan.jenisSimpanan', 'pinjaman.angsuran'])->findOrFail($id);
        
        $jenisList = JenisSimpanan::all();
        $saldoPerJenis = [];
        foreach ($jenisList as $j) {
            $saldoPerJenis[$j->kode_jenis] = [
                'nama' => $j->nama_simpanan,
                'saldo' => $anggota->getSaldoPerJenis($j->id),
            ];
        }

        return view('admin.anggota.show', compact('anggota', 'saldoPerJenis'));
    }

    public function edit($id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);
        return view('admin.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $request->validate([
            'nama_lengkap'         => 'required|string|max:100',
            'nik'                  => 'required|string|size:16|unique:anggota,nik,' . $anggota->id,
            'jenis_kelamin'        => 'required|in:L,P',
            'no_hp'                => 'required|string|max:15',
            'alamat'               => 'required|string',
            'pekerjaan'            => 'required|string|max:100',
            'penghasilan_per_bulan'=> 'required|numeric|min:0',
            'status_pernikahan'    => 'required|in:Belum Menikah,Menikah,Cerai',
        ]);

        $anggota->update($request->only([
            'nama_lengkap', 'nik', 'jenis_kelamin', 'no_hp', 'alamat',
            'pekerjaan', 'penghasilan_per_bulan', 'status_pernikahan'
        ]));

        return redirect()->route('admin.anggota.show', $anggota->id)
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $anggota = Anggota::findOrFail($id);
        $newStatus = ($anggota->status_anggota === 'aktif') ? 'non-aktif' : 'aktif';
        $anggota->update(['status_anggota' => $newStatus]);

        if ($anggota->user) {
            $anggota->user->update(['status_aktif' => ($newStatus === 'aktif')]);
        }

        return back()->with('success', "Status anggota {$anggota->nama_lengkap} diubah menjadi {$newStatus}.");
    }
}

