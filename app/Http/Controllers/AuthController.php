<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withInput($request->only('username'))
                ->with('error', 'Username atau password salah.');
        }

        $user = Auth::user();

        // Cek status aktif
        if (!$user->status_aktif) {
            Auth::logout();
            return back()->withInput($request->only('username'))
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi admin.');
        }

        $request->session()->regenerate();

        return $this->redirectByRole($user->role);
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    /**
     * Helper: redirect sesuai role.
     */
    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'pimpinan' => redirect()->route('pimpinan.dashboard'),
            'anggota'  => redirect()->route('anggota.dashboard'),
            default    => redirect()->route('login')->with('error', 'Role tidak dikenali.'),
        };
    }
}
