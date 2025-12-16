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

        $users->getCollection()->transform(function ($user) {

            $plans = WeeklyPlan::where('user_id', $user->id)
                ->where('week', 1)
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

    public function show($id)
    {
        $user = User::with('preference')->findOrFail($id);

        $rawPlans = WeeklyPlan::where('user_id', $id)
            ->with('menu')
            ->orderBy('week')
            ->orderBy('day')
            ->get();

        $week1Plans = $rawPlans->where('week', 1);
        $totalPlansWeek1 = $week1Plans->count();
        $completedPlansWeek1 = $week1Plans->where('is_completed', true)->count();

        if ($totalPlansWeek1 > 0) {
            $percentage = ($completedPlansWeek1 / $totalPlansWeek1) * 100;
        } else {
            $percentage = 0;
        }

        $hasPlan = $rawPlans->count() > 0;

        $formattedPlans = [];
        foreach ($rawPlans as $plan) {
            $formattedPlans[$plan->week][$plan->day] = $plan;
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

        return view('adminF/user_detail', compact('user', 'formattedPlans', 'percentage', 'hasPlan', 'dayNames', 'completedPlansWeek1', 'totalPlansWeek1'));
    }
}
