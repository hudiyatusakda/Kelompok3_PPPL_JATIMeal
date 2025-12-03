<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    public function index()
    {
        if(UserPreference::where('user_id', Auth::id())->exists()){
            return redirect('/dashboard');
        }
        return view('personal');
    }

    public function store(Request $request)
    {
        $request->validate([
            'protein' => 'required',
            'price' => 'required',
            'style' => 'required',
            'side_dish' => 'required',
            'veggies' => 'required',
        ]);

        UserPreference::create([
            'user_id' => Auth::id(),
            'protein_preference' => $request->protein,
            'price_range' => $request->price,
            'cooking_style' => $request->style,
            'has_side_dish' => $request->side_dish == 'yes',
            'has_vegetables' => $request->veggies == 'yes',
        ]);

        return redirect('/dashboard')->with('success', 'Preferensi berhasil disimpan! Berikut rekomendasi menu untukmu.'); // <--- UBAH INI
    }
}