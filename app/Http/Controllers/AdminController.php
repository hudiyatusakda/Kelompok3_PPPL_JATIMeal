<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\DailyLog;

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
        $user = User::with('preference')->findOrFail($id);

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // 1. CEK KEBERADAAN DATA (PLAN)
        // Apakah user ini punya log di minggu ini?
        $checkPlan = DailyLog::where('user_id', $user->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->exists();

        $weeklyProgress = [];
        $percentage = 0;
        $hasPlan = false; // Default false

        // 2. JIKA DATA ADA, BARU KITA HITUNG (Kalkulasi seperti sebelumnya)
        if ($checkPlan) {
            $hasPlan = true; // Tandai bahwa user punya rencana
            $completedCount = 0;

            for ($i = 0; $i < 7; $i++) {
                $currentDate = $startOfWeek->copy()->addDays($i);

                $log = DailyLog::where('user_id', $user->id)
                    ->whereDate('date', $currentDate->format('Y-m-d'))
                    ->first();

                $status = '-';
                if ($log) {
                    $status = ucfirst($log->status);
                    if ($log->status == 'selesai') {
                        $completedCount++;
                    }
                }

                $weeklyProgress[] = [
                    'day_name' => 'Hari ' . ($i + 1),
                    'date' => $currentDate->format('d M'),
                    'status' => $status
                ];
            }

            $percentage = ($completedCount / 7) * 100;
        }

        // Kirim variabel $hasPlan ke view
        return view('adminF.user_detail', compact('user', 'weeklyProgress', 'percentage', 'hasPlan'));
    }
}
