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
                return explode(',', $item);
            })
            ->flatten()
            ->map(function ($item) {
                return trim(str_replace('.', '', strtolower($item)));
            })
            ->filter(function ($item) {
                return $item != '' && strlen($item) > 1;
            })
            ->unique()
            ->map(function ($item) {
                return ucwords($item);
            })
            ->sort()
            ->values();

        $query = Menu::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_menu', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('ingredient') && $request->ingredient != '') {
            $query->where('bahan_baku', 'LIKE', '%' . $request->ingredient . '%');
        }

        if (!$request->has('search') && (!$request->has('ingredient') || $request->ingredient == '') && $preference) {

            if ($preference->protein_preference && $preference->protein_preference !== 'Semua') {
                $query->where('bahan_baku', 'LIKE', '%' . $preference->protein_preference . '%');
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
        $menus = Menu::where('kategori', $category)->paginate(12);

        return view('menu_category', compact('menus', 'category'));
    }

    public function showMenu($id)
    {
        $menu = Menu::findOrFail($id);

        $lastWeek = \App\Models\WeeklyPlan::where('user_id', Auth::id())->max('week');

        $currentMaxWeek = $lastWeek ?? 0;

        return view('menu_detail', compact('menu', 'currentMaxWeek'));
    }
}
