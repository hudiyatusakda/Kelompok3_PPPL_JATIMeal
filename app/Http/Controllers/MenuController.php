<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function store(Request $request)
    {
        // 1. BERSIHKAN FORMAT RUPIAH SEBELUM VALIDASI
        // Mengubah "Rp 50.000" menjadi "50000"
        // Hapus semua karakter yang BUKAN angka
        $request->merge([
            'harga_bahan' => preg_replace('/\D/', '', $request->harga_bahan)
        ]);

        // 2. Lanjutkan Validasi seperti biasa (sekarang harga_bahan sudah bersih jadi angka)
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'required|string',
            'bahan_baku' => 'required|string|max:255',
            'harga_bahan' => 'required|numeric|min:0', // Validasi numeric akan berhasil
            'referensi' => 'nullable|string',
            'gambar_menu' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'required|string',
        ]);

        // ... sisa kode simpan gambar dan create menu tetap sama ...

        // Simpan ke Database
        $imagePath = $request->file('gambar_menu')->store('menu-images', 'public');

        Menu::create([
            'nama_menu' => $request->nama_menu,
            'kategori' => $request->kategori,
            'bahan_baku' => $request->bahan_baku,
            'harga_bahan' => $request->harga_bahan, // Data yang masuk sudah bersih (integer)
            'referensi' => $request->referensi,
            'gambar_path' => $imagePath,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan!');
    }

    public function index(Request $request)
    {
        $query = Menu::query();

        if ($request->filled('search')) {
            $query->where('nama_menu', 'LIKE', '%' . $request->search . '%');
        }

        $menus = $query->get()->groupBy('kategori');

        return view('adminF.list_menu', compact('menus'));
    }

    public function create()
    {
        return view('adminF.dash_admin');
    }

    // edit menu
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('adminF.edit_menu', compact('menu'));
    }

    // Proses Update Data ke Database
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        // 1. BERSIHKAN FORMAT RUPIAH
        $request->merge([
            'harga_bahan' => preg_replace('/\D/', '', $request->harga_bahan)
        ]);

        // 2. Validasi
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'required|string',
            'bahan_baku' => 'required|string|max:255',
            'harga_bahan' => 'required|numeric|min:0',
            'referensi' => 'nullable|string',
            'gambar_menu' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'required|string',
        ]);

        // ... sisa kode update gambar dan data tetap sama ...

        $menu->nama_menu = $request->nama_menu;
        $menu->kategori = $request->kategori;
        $menu->bahan_baku = $request->bahan_baku;
        $menu->harga_bahan = $request->harga_bahan; // Tersimpan sebagai integer
        $menu->referensi = $request->referensi;
        $menu->deskripsi = $request->deskripsi;

        // Cek upload gambar (kode lama Anda)...
        if ($request->hasFile('gambar_menu')) {
            // ... logika hapus dan upload gambar ...
            $imagePath = $request->file('gambar_menu')->store('menu-images', 'public');
            $menu->gambar_path = $imagePath;
        }

        $menu->save();

        return redirect()->route('menu.index')->with('success', 'Menu berhasil diperbaharui!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        // 1. Hapus gambar dari storage
        if ($menu->gambar_path && Storage::exists('public/' . $menu->gambar_path)) {
            Storage::delete('public/' . $menu->gambar_path);
        }

        // 2. Hapus data dari database
        $menu->delete();

        // 3. Kembali ke list menu
        return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus!');
    }
}
