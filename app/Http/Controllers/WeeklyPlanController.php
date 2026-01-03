<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeeklyPlan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WeeklyPlanController extends Controller
{
    // 1. TAMPILKAN DAFTAR MINGGU (OVERVIEW)
    public function index(Request $request)
    {
        // Ambil filter bulan/tahun (Default: Hari ini)
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // 1. Tentukan tanggal 1 bulan tersebut
        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // 2. Tarik mundur ke SENIN pertama di tampilan kalender
        // (Meskipun tanggalnya masuk bulan sebelumnya)
        $startCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);

        // 3. Tarik maju ke MINGGU terakhir
        $endCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $weeksData = [];
        $currentDate = $startCalendar->copy();
        $weekCounter = 1;

        // 4. Loop per minggu
        while ($currentDate <= $endCalendar) {
            $weekStart = $currentDate->copy();
            $weekEnd = $currentDate->copy()->endOfWeek(Carbon::SUNDAY);

            // Ini solusi agar menu tersimpan & muncul
            $plans = WeeklyPlan::where('user_id', Auth::id())
                ->whereBetween('planned_date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
                ->get();

            $total = $plans->count();
            $completed = $plans->where('is_completed', true)->count();
            $percent = $total > 0 ? ($completed / $total) * 100 : 0;

            $weeksData[] = [
                'week_label' => 'Minggu ' . $weekCounter,
                'date_range' => $weekStart->format('d M') . ' - ' . $weekEnd->format('d M'),
                'start_date' => $weekStart->format('Y-m-d'), // Kunci untuk Link
                'total' => $total,
                'completed' => $completed,
                'percent' => round($percent),
                'is_current' => Carbon::now()->between($weekStart, $weekEnd)
            ];

            $currentDate->addWeek();
            $weekCounter++;
        }

        // Logic Overdue (Menu Terlewat)
        $overduePlans = WeeklyPlan::where('user_id', Auth::id())
            ->where('is_completed', false)
            ->where('planned_date', '<', Carbon::now()->format('Y-m-d'))
            ->with('menu')
            ->get();

        return view('weekly_overview', compact('weeksData', 'month', 'year', 'overduePlans'));
    }

    // 2. TAMPILKAN DETAIL SATU MINGGU
    public function showWeek(Request $request)
    {
        // Terima parameter start_date
        $startDate = Carbon::parse($request->start_date);
        $endDate = $startDate->copy()->endOfWeek(Carbon::SUNDAY);

        // Generate Hari Senin-Minggu
        $days = [];
        $temp = $startDate->copy();
        for ($i = 0; $i < 7; $i++) {
            $days[] = [
                'date' => $temp->format('Y-m-d'),
                'day_name' => $temp->locale('id')->isoFormat('dddd'),
                'display_date' => $temp->format('d M'),
                'is_today' => $temp->isToday()
            ];
            $temp->addDay();
        }

        // Ambil Plan
        $plans = WeeklyPlan::where('user_id', Auth::id())
            ->whereBetween('planned_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->with('menu')
            ->get()
            ->keyBy('planned_date');

        // Data untuk Header/Navigasi
        $weekNumber = $startDate->weekOfMonth;
        $month = $startDate->month;
        $year = $startDate->year;

        return view('weekly_detail', compact('plans', 'days', 'startDate', 'weekNumber', 'month', 'year'));
    }

    // 3. SIMPAN MENU
    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required',
            'planned_date' => 'required|date',
        ]);

        $date = Carbon::parse($request->planned_date);

        WeeklyPlan::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'planned_date' => $date->format('Y-m-d'),
            ],
            [
                'menu_id' => $request->menu_id,
                'week' => $date->weekOfMonth,
                'month' => $date->month,
                'year' => $date->year,
                'day_of_week' => $date->dayOfWeekIso,
                'is_completed' => false
            ]
        );

        // Redirect ke minggu tempat tanggal itu berada
        $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');

        return redirect()->route('weekly.show', ['start_date' => $weekStart])
            ->with('success', 'Menu berhasil dijadwalkan!');
    }

    public function edit($id)
    {
        $plan = WeeklyPlan::where('user_id', Auth::id())->with('menu')->findOrFail($id);
        return view('weekly_menu_detail', compact('plan'));
    }

    public function destroy($id)
    {
        $plan = WeeklyPlan::where('user_id', Auth::id())->findOrFail($id);
        $date = Carbon::parse($plan->planned_date);
        $plan->delete();
        return redirect()->route('weekly.show', ['start_date' => $date->startOfWeek()->format('Y-m-d')]);
    }

    public function complete($id)
    {
        $plan = WeeklyPlan::where('user_id', Auth::id())->findOrFail($id);
        $plan->is_completed = !$plan->is_completed;
        $plan->save();
        return redirect()->back();
    }
}
