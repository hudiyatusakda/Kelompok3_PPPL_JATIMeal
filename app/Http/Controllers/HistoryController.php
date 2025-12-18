<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeeklyPlan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Filter Bulan & Tahun (Default: Bulan Lalu, karena ini riwayat)
        // Jika tidak ada request, kita ambil bulan lalu.
        $month = $request->input('month', Carbon::now()->subMonth()->month);
        $year = $request->input('year', Carbon::now()->year);

        // 2. Ambil data plan berdasarkan filter
        $history = WeeklyPlan::where('user_id', Auth::id())
            ->where('month', $month)
            ->where('year', $year)
            ->with('menu')
            ->orderBy('week', 'asc')
            ->orderBy('day_of_week', 'asc') // Urutkan Senin-Minggu
            ->get()
            ->groupBy('week');

        return view('riwayat_menu', compact('history', 'month', 'year'));
    }

    // COPY SATU MINGGU FULL KE BULAN SEKARANG
    public function restoreFull(Request $request)
    {
        $sourceWeek = $request->source_week;
        $sourceMonth = $request->source_month;
        $sourceYear = $request->source_year;

        // Ambil data sumber
        $plansToCopy = WeeklyPlan::where('user_id', Auth::id())
            ->where('week', $sourceWeek)
            ->where('month', $sourceMonth)
            ->where('year', $sourceYear)
            ->get();

        if ($plansToCopy->isEmpty()) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        // Tentukan Tujuan: Minggu Baru di Bulan SAAT INI
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $lastWeekInCurrentMonth = WeeklyPlan::where('user_id', Auth::id())
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->max('week') ?? 0;

        $newWeek = $lastWeekInCurrentMonth + 1;

        // Proses Copy
        foreach ($plansToCopy as $plan) {
            WeeklyPlan::create([
                'user_id' => Auth::id(),
                'menu_id' => $plan->menu_id,
                'week' => $newWeek,            // Minggu Baru
                'day_of_week' => $plan->day_of_week, // Copy Harinya juga
                'month' => $currentMonth,      // Bulan Sekarang
                'year' => $currentYear,        // Tahun Sekarang
                'is_completed' => false
            ]);
        }

        return redirect()->route('weekly.index')->with('success', 'Paket menu berhasil disalin ke Minggu ' . $newWeek . ' bulan ini.');
    }

    // COPY SATU MENU KE MINGGU SAAT INI (QUICK ADD)
    public function restoreSingle(Request $request)
    {
        // Masukkan ke Minggu Terakhir di Bulan Ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $targetWeek = WeeklyPlan::where('user_id', Auth::id())
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->max('week');

        // Jika bulan ini belum ada jadwal sama sekali, buat Minggu 1
        if (!$targetWeek) {
            $targetWeek = 1;
        }

        WeeklyPlan::create([
            'user_id' => Auth::id(),
            'menu_id' => $request->menu_id,
            'week' => $targetWeek,
            'day_of_week' => null, // Biarkan null agar user atur harinya nanti di detail
            'month' => $currentMonth,
            'year' => $currentYear,
            'is_completed' => false
        ]);

        return redirect()->route('weekly.show', ['week' => $targetWeek, 'month' => $currentMonth, 'year' => $currentYear])
            ->with('success', 'Menu ditambahkan ke Minggu ' . $targetWeek . '. Silakan atur harinya.');
    }
}
