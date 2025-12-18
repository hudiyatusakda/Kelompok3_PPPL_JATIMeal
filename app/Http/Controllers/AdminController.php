<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\DailyLog;
use App\Models\WeeklyPlan;
use App\Models\Menu;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalMenuIdeas = DailyLog::count();

        $users = User::where('role', 'user')->paginate(5);

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $users->getCollection()->transform(function ($user) use ($currentMonth, $currentYear) {

            $plans = WeeklyPlan::where('user_id', $user->id)
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->get();

            $totalPlans = $plans->count();
            $completedPlans = $plans->where('is_completed', true)->count();

            if ($totalPlans > 0) {
                $percentage = ($completedPlans / $totalPlans) * 100;
            } else {
                $percentage = 0;
            }

            $user->weekly_progress = round($percentage);
            return $user;
        });

        return view('adminF.pengelola_pengguna', compact('users', 'totalUsers', 'totalMenuIdeas'));
    }

    public function show(Request $request, $id)
    {
        $user = User::with('preference')->findOrFail($id);

        // 1. Ambil opsi Tahun dan Bulan yang tersedia di database
        $availablePeriods = WeeklyPlan::where('user_id', $id)
            ->select('month', 'year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // 2. LOGIKA BARU: Penentuan Bulan & Tahun
        if ($request->has('month') && $request->has('year')) {
            $selectedMonth = $request->input('month');
            $selectedYear = $request->input('year');
        } elseif ($availablePeriods->isNotEmpty()) {
            $latestPeriod = $availablePeriods->first();
            $selectedMonth = $latestPeriod->month;
            $selectedYear = $latestPeriod->year;
        } else {
            $selectedMonth = Carbon::now()->month;
            $selectedYear = Carbon::now()->year;
        }

        // 3. Ambil Plan sesuai filter
        $rawPlans = WeeklyPlan::where('user_id', $id)
            ->where('month', $selectedMonth)
            ->where('year', $selectedYear)
            ->with('menu')
            ->orderBy('week')
            ->orderBy('day_of_week')
            ->get();

        // 4. Hitung Statistik
        $totalPlansMonth = $rawPlans->count();
        $completedPlansMonth = $rawPlans->where('is_completed', true)->count();

        $percentage = ($totalPlansMonth > 0)
            ? ($completedPlansMonth / $totalPlansMonth) * 100
            : 0;

        $hasPlan = $rawPlans->count() > 0;

        // 5. Format Data Array
        $formattedPlans = [];
        foreach ($rawPlans as $plan) {
            $dayKey = $plan->day ?? $plan->day_of_week;

            if ($dayKey) {
                $formattedPlans[$plan->week][$dayKey] = $plan;
            }
        }

        $dayNames = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];

        try {
            $monthName = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->translatedFormat('F Y');
        } catch (\Exception $e) {
            $monthName = '-';
        }

        return view('adminF/user_detail', compact(
            'user',
            'formattedPlans',
            'percentage',
            'hasPlan',
            'dayNames',
            'completedPlansMonth',
            'totalPlansMonth',
            'availablePeriods',
            'selectedMonth',
            'selectedYear',
            'monthName'
        ));
    }
}
