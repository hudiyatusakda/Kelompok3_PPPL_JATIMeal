<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('Register');
    }
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Pastikan email unik di tabel users
            'password' => 'required|string|min:8|confirmed', // 'confirmed' akan mengecek input name="password_confirmation"
        ]);

        // 2. Simpan ke Database
        // Kita menggunakan Eloquent Model 'User'
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            // Password WAJIB di-hash (enkripsi) agar aman
            'password' => Hash::make($request->password),
        ]);

        // 3. Redirect setelah sukses
        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
