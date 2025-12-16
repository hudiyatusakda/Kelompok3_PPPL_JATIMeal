<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use App\Models\UserPreference;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $preference = $user->preference;

        $allIngredients = Menu::pluck('bahan_baku')
            ->map(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        // 2. Query Dasar
        $query = Menu::query();

        // --- FITUR PENCARIAN (SEARCH) ---
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('nama_menu', 'LIKE', '%' . $searchTerm . '%');
        }

        // --- FITUR FILTER BAHAN BAKU ---
        if ($request->has('ingredient') && $request->ingredient != '') {
            $ingredient = $request->ingredient;
            $query->where('bahan_baku', 'LIKE', '%' . $ingredient . '%');
        }

        if (!$request->has('search') && !$request->has('ingredient') && $preference) {

            if ($preference->protein_preference && $preference->protein_preference !== 'Semua') {
                $query->where('bahan_baku', 'LIKE', '%' . $preference->protein_preference . '%');
            }

            if ($preference->cooking_style) {
                $query->where(function ($q) use ($preference) {
                    $q->where('kategori', 'LIKE', '%' . $preference->cooking_style . '%')
                        ->orWhere('referensi', 'LIKE', '%' . $preference->cooking_style . '%');
                });
            }

            if ($preference->price_range) {
                $priceLimits = $this->parsePriceRange($preference->price_range);
                if ($priceLimits) {
                    $query->whereBetween('harga_bahan', [$priceLimits['min'], $priceLimits['max']]);
                }
            }
        }

        $recommendedMenus = $query->limit(8)->get();

        $isFallback = false;
        if ($recommendedMenus->isEmpty() && !$request->has('search') && !$request->has('ingredient')) {
            $recommendedMenus = Menu::inRandomOrder()->limit(8)->get();
            $isFallback = true;
        }

        $menusByCategory = Menu::all()->groupBy('kategori');

        return view('dashboard', compact('recommendedMenus', 'isFallback', 'menusByCategory', 'allIngredients'));
    }
    private function parsePriceRange($rangeString)
    {
        $clean = str_replace(['Rp', '.', ' '], '', $rangeString);
        if (str_contains($clean, '<')) {
            $val = (int) filter_var($clean, FILTER_SANITIZE_NUMBER_INT);
            return ['min' => 0, 'max' => $val * 1000];
        }
        if (str_contains($clean, '>')) {
            $val = (int) filter_var($clean, FILTER_SANITIZE_NUMBER_INT);
            return ['min' => $val * 1000, 'max' => 9999999];
        }
        if (str_contains($clean, '-')) {
            $parts = explode('-', $clean);
            $min = (int) filter_var($parts[0], FILTER_SANITIZE_NUMBER_INT);
            $max = (int) filter_var($parts[1], FILTER_SANITIZE_NUMBER_INT);
            return ['min' => $min * 1000, 'max' => $max * 1000];
        }
        return null;
    }

    public function showCategory($category)
    {
        // 1. Ambil menu berdasarkan kolom 'kategori'=
        $menus = Menu::where('kategori', $category)->paginate(12);

        // 2. Kirim data ke view baru
        return view('menu_category', compact('menus', 'category'));
    }

    public function showMenu($id)
    {
        // Ambil data menu berdasarkan ID, jika tidak ada tampilkan 404
        $menu = Menu::findOrFail($id);

        // Cari minggu terakhir yang dimiliki user
        $lastWeek = \App\Models\WeeklyPlan::where('user_id', Auth::id())->max('week');

        // Jika belum punya, default 0
        $currentMaxWeek = $lastWeek ?? 0;

        return view('menu_detail', compact('menu', 'currentMaxWeek'));
    }
}
