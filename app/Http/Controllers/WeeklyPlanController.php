<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\WeeklyPlan;
use Illuminate\Support\Facades\Auth;

class WeeklyPlanController extends Controller
{
    public function index()
    {
        // Ambil data plan user, urutkan berdasarkan minggu
        $plans = WeeklyPlan::where('user_id', Auth::id())
            ->with('menu')
            ->orderBy('week')
            ->get()
            ->groupBy('week');

        return view('menu_mingguan', compact('plans'));
    }

    // MENYIMPAN MENU KE JADWAL (Action dari Modal)
    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'week' => 'required|integer|min:1',
            'day'     => 'required|integer|between:1,7'
        ]);

        WeeklyPlan::create([
            'user_id' => Auth::id(),
            'menu_id' => $request->menu_id,
            'week' => $request->week,
            'day'     => $request->day
        ]);

        return redirect()->route('weekly.index')->with('success', 'Menu berhasil ditambahkan ke Minggu ' . $request->week);
    }

    // MENAMPILKAN HALAMAN EDIT
    public function edit($id)
    {
        $plan = WeeklyPlan::where('user_id', Auth::id())
            ->with('menu')
            ->findOrFail($id);

        return view('weekly_menu_detail', compact('plan'));
    }

    // MENGHAPUS MENU DARI JADWAL
    public function destroy($id)
    {
        $plan = WeeklyPlan::where('user_id', Auth::id())->findOrFail($id);

        $week = $plan->week;

        $plan->delete();

        return redirect()->route('weekly.index')->with('success', 'Menu berhasil dihapus dari Minggu ' . $week);
    }
}
