<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'required|string',
            'gambar_menu' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'deskripsi' => 'required|string',
        ]);

        // 2. Proses Upload Gambar
        $imagePath = $request->file('gambar_menu')->store('menu-images', 'public');

        // 3. Simpan ke Database
        Menu::create([
            'nama_menu' => $request->nama_menu,
            'kategori' => $request->kategori,
            'gambar' => $imagePath,
            'deskripsi' => $request->deskripsi,
        ]);

        // 4. Redirect kembali (Refresh halaman)
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

        // 1. Validasi (Gambar nullable karena user mungkin tidak ganti gambar)
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori'  => 'required|string',
            'gambar_menu' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'required|string',
        ]);

        // 2. Cek apakah ada upload gambar baru
        if ($request->hasFile('gambar_menu')) {
            if ($menu->gambar && Storage::exists('public/' . $menu->gambar)) {
                Storage::delete('public/' . $menu->gambar);
            }

            // Simpan gambar baru
            $imagePath = $request->file('gambar_menu')->store('menu-images', 'public');
            $menu->gambar = $imagePath;
        }

        // 3. Update Text
        $menu->nama_menu = $request->nama_menu;
        $menu->kategori = $request->kategori;
        $menu->deskripsi = $request->deskripsi;

        $menu->save();

        // 4. Kembali ke List Menu
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
