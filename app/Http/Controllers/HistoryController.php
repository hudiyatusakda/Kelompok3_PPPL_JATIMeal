<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeeklyPlan;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $history = WeeklyPlan::where('user_id', Auth::id())
            ->with('menu')
            ->orderBy('week', 'desc')
            ->get()
            ->groupBy('week');

        return view('riwayat_menu', compact('history'));
    }

    public function restoreSingle(Request $request)
    {
        $lastWeek = WeeklyPlan::where('user_id', Auth::id())->max('week') ?? 0;

        $targetWeek = $lastWeek > 0 ? $lastWeek : 1;

        WeeklyPlan::create([
            'user_id' => Auth::id(),
            'menu_id' => $request->menu_id,
            'week' => $targetWeek,
            'is_completed' => false
        ]);

        return redirect()->route('weekly.index')->with('success', 'Menu berhasil ditambahkan ke Minggu ' . $targetWeek);
    }

    public function restoreFull(Request $request)
    {
        $sourceWeek = $request->source_week;

        $plansToCopy = WeeklyPlan::where('user_id', Auth::id())
            ->where('week', $sourceWeek)
            ->get();

        if ($plansToCopy->isEmpty()) {
            return back()->with('error', 'Data minggu tersebut kosong.');
        }

        $lastWeek = WeeklyPlan::where('user_id', Auth::id())->max('week') ?? 0;
        $newWeek = $lastWeek + 1;

        foreach ($plansToCopy as $plan) {
            WeeklyPlan::create([
                'user_id' => Auth::id(),
                'menu_id' => $plan->menu_id,
                'week' => $newWeek,
                'is_completed' => false
            ]);
        }

        return redirect()->route('weekly.index')->with('success', 'Paket Minggu ' . $sourceWeek . ' berhasil disalin menjadi Minggu ' . $newWeek);
    }
}
