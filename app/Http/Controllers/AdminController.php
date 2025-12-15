<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\DailyLog;
use App\Models\WeeklyPlan;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Hitung Total User (Role User)
        $totalUsers = User::where('role', 'user')->count();

        // 2. Hitung Total Ide Menu (Kita ambil dari total rencana yang dibuat semua user)
        $totalMenuIdeas = DailyLog::count();

        // 3. Ambil User dengan Pagination & Hitung Progres Mingguan mereka
        $users = User::where('role', 'user')->paginate(5);

        // Tentukan Range Minggu Ini
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Kita modifikasi collection di dalam paginator
        $users->getCollection()->transform(function ($user) use ($startOfWeek, $endOfWeek) {

            // Hitung berapa menu yang statusnya 'selesai' minggu ini
            $completedCount = DailyLog::where('user_id', $user->id)
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->where('status', 'selesai')
                ->count();

            // Hitung persentase (Maksimal 7 hari)
            // Jika completed 7 hari -> 100%, jika 0 -> 0%
            $percentage = ($completedCount / 7) * 100;

            // Simpan ke dalam object user sementara (untuk ditampilkan di view)
            $user->weekly_progress = round($percentage);

            return $user;
        });

        return view('adminF/pengelola_pengguna', compact('users', 'totalUsers', 'totalMenuIdeas'));
    }

    public function show($id)
    {
        // 1. Ambil User
        $user = User::with('preference')->findOrFail($id);

        // 2. Ambil Weekly Plan User & Load Relasi Menu
        $rawPlans = WeeklyPlan::where('user_id', $id)
            ->with('menu')
            ->orderBy('week')
            ->orderBy('day')
            ->get();

        // 3. Kelompokkan Data: [Minggu ke-X] => [Hari ke-Y] => Data Plan
        $formattedPlans = [];
        foreach ($rawPlans as $plan) {
            $formattedPlans[$plan->week][$plan->day] = $plan;
        }

        // 4. Hitung Persentase (Opsional: Misal berdasarkan kelengkapan jadwal minggu ini)
        $currentWeek = 1; // Default minggu 1
        $filledDays = isset($formattedPlans[$currentWeek]) ? count($formattedPlans[$currentWeek]) : 0;
        $percentage = ($filledDays / 7) * 100;
        $hasPlan = $rawPlans->count() > 0;

        // 5. Array Nama Hari untuk Label
        $dayNames = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];

        return view('adminF/user_detail', compact('user', 'formattedPlans', 'percentage', 'hasPlan', 'dayNames'));
    }
}
