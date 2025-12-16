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
        $plans = WeeklyPlan::where('user_id', Auth::id())
            ->with('menu')
            ->orderBy('week')
            ->get()
            ->groupBy('week');

        return view('menu_mingguan', compact('plans'));
    }

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

    public function edit($id)
    {
        $plan = WeeklyPlan::where('user_id', Auth::id())
            ->with('menu')
            ->findOrFail($id);

        return view('weekly_menu_detail', compact('plan'));
    }

    public function destroy($id)
    {
        $plan = WeeklyPlan::where('user_id', Auth::id())->findOrFail($id);

        $week = $plan->week;

        $plan->delete();

        return redirect()->route('weekly.index')->with('success', 'Menu berhasil dihapus dari Minggu ' . $week);
    }

    public function complete($id)
    {
        $plan = WeeklyPlan::where('user_id', Auth::id())->findOrFail($id);

        $plan->is_completed = !$plan->is_completed;
        $plan->save();

        $status = $plan->is_completed ? 'selesai' : 'belum selesai';

        return redirect()->back()->with('success', 'Status menu berhasil diubah menjadi ' . $status);
    }
}
