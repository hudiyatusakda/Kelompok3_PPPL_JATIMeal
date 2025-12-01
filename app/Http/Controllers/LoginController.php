<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // 1. Menampilkan Form Login
    public function index()
    {
        return view('login'); // Pastikan nanti file view-nya bernama login.blade.php
    }

    // 2. Memproses Login
    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek credentials (email & password)
        // Auth::attempt otomatis meng-hash password input dan membandingkan dengan di DB
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Security: Mencegah session fixation

            // Jika berhasil, alihkan ke dashboard atau halaman utama
            return redirect()->intended('dashboard');
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}