<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\WeeklyPlan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WeeklyPlanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filter Bulan & Tahun (Logic Lama)
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // 2. Logic Lama (Current Week & Stats)
        $currentDate = Carbon::now();
        $isCurrentMonth = ($currentDate->month == $month && $currentDate->year == $year);
        $currentWeekNumber = $isCurrentMonth ? $currentDate->weekOfMonth : 0;

        $weeksData = [];
        for ($i = 1; $i <= 5; $i++) {
            $plans = WeeklyPlan::where('user_id', Auth::id())
                ->where('month', $month)
                ->where('year', $year)
                ->where('week', $i)
                ->get();

            $totalMenu = $plans->count();
            $completedMenu = $plans->where('is_completed', true)->count();
            $percent = $totalMenu > 0 ? ($completedMenu / $totalMenu) * 100 : 0;

            $weeksData[$i] = [
                'total' => $totalMenu,
                'completed' => $completedMenu,
                'percent' => round($percent),
                'status_class' => $percent == 100 && $totalMenu > 0 ? 'full-done' : ($percent > 0 ? 'in-progress' : 'empty')
            ];
        }

        // --- 3. LOGIC BARU: CEK MENU TERLEWAT (OVERDUE) ---
        $allIncomplete = WeeklyPlan::where('user_id', Auth::id())
            ->where('is_completed', false)
            ->with('menu')
            ->get();

        $overduePlans = $allIncomplete->filter(function ($plan) {
            try {
                $planDate = Carbon::createFromDate($plan->year, $plan->month, 1)
                    ->addWeeks($plan->week - 1)
                    ->startOfWeek()
                    ->addDays($plan->day_of_week - 1);

                return $planDate->isPast() && !$planDate->isToday();
            } catch (\Exception $e) {
                return false;
            }
        });

        return view('weekly_overview', compact('weeksData', 'month', 'year', 'currentWeekNumber', 'overduePlans'));
    }

    public function showWeek(Request $request)
    {
        $week = $request->week;
        $month = $request->month;
        $year = $request->year;

        $plans = WeeklyPlan::where('user_id', Auth::id())
            ->where('week', $week)
            ->where('month', $month)
            ->where('year', $year)
            ->with('menu')
            ->get()
            ->keyBy('day_of_week');

        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];

        return view('weekly_detail', compact('plans', 'days', 'week', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required',
            'week' => 'required',
            'day_of_week' => 'required',
        ]);

        WeeklyPlan::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'week' => $request->week,
                'day_of_week' => $request->day_of_week,
                'month' => Carbon::now()->month,
                'year' => Carbon::now()->year,
            ],
            [
                'menu_id' => $request->menu_id,
                'is_completed' => false
            ]
        );

        return redirect()->route('weekly.show', [
            'week' => $request->week,
            'month' => Carbon::now()->month,
            'year' => Carbon::now()->year
        ])->with('success', 'Menu berhasil dijadwalkan!');
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
