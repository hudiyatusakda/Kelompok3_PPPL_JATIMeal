<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function store(Request $request)
    {
        $request->merge([
            'harga_bahan' => preg_replace('/\D/', '', $request->harga_bahan)
        ]);

        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'required|string',
            'bahan_baku' => 'required|string|max:255',
            'harga_bahan' => 'required|numeric|min:0',
            'referensi' => 'nullable|string',
            'gambar_menu' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'required|string',
        ]);

        $imagePath = $request->file('gambar_menu')->store('menu-images', 'public');

        Menu::create([
            'nama_menu' => $request->nama_menu,
            'kategori' => $request->kategori,
            'bahan_baku' => $request->bahan_baku,
            'harga_bahan' => $request->harga_bahan,
            'referensi' => $request->referensi,
            'gambar' => $imagePath,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan!');
    }

    public function index(Request $request)
    {
        $query = Menu::query();

        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('search')) {
            $query->where('nama_menu', 'LIKE', '%' . $request->search . '%');
        }

        $menus = $query->get()->groupBy('kategori');

        $currentCategory = $request->kategori ?? 'Semua Menu';

        return view('adminF.list_menu', compact('menus', 'currentCategory'));
    }

    public function create()
    {
        return view('adminF.dash_admin');
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('adminF.edit_menu', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->merge([
            'harga_bahan' => preg_replace('/\D/', '', $request->harga_bahan)
        ]);

        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'required|string',
            'bahan_baku' => 'required|string|max:255',
            'harga_bahan' => 'required|numeric|min:0',
            'referensi' => 'nullable|string',
            'gambar_menu' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'required|string',
        ]);

        $menu->nama_menu = $request->nama_menu;
        $menu->kategori = $request->kategori;
        $menu->bahan_baku = $request->bahan_baku;
        $menu->harga_bahan = $request->harga_bahan;
        $menu->referensi = $request->referensi;
        $menu->deskripsi = $request->deskripsi;

        if ($request->hasFile('gambar_menu')) {
            $imagePath = $request->file('gambar_menu')->store('menu-images', 'public');
            $menu->gambar_pa = $imagePath;
        }

        $menu->save();

        return redirect()->route('menu.index')->with('success', 'Menu berhasil diperbaharui!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        if ($menu->gambar && Storage::exists('public/' . $menu->gambar)) {
            Storage::delete('public/' . $menu->gambar);
        }

        $menu->delete();

        return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus!');
    }
}
