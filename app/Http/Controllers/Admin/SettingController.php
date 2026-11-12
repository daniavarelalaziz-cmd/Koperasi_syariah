<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        $qrisString = Setting::get('qris_string', '');

        return view('admin.pengaturan.index', compact('settings', 'qrisString'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'koperasi_nama'    => 'required|string|max:200',
            'koperasi_alamat'  => 'nullable|string|max:500',
            'koperasi_telp'    => 'nullable|string|max:50',
            'qris_string'      => 'nullable|string',
            'qris_nama_merchant' => 'nullable|string|max:100',
        ]);

        Setting::set('koperasi_nama',    $request->koperasi_nama);
        Setting::set('koperasi_alamat',  $request->koperasi_alamat ?? '');
        Setting::set('koperasi_telp',    $request->koperasi_telp ?? '');
        Setting::set('qris_string',      trim($request->qris_string ?? ''));
        Setting::set('qris_nama_merchant', $request->qris_nama_merchant ?? '');

        return redirect()->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan berhasil disimpan!');
    }
}

