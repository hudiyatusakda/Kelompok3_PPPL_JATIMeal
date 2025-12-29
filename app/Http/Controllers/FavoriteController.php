<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('menu')
            ->latest()
            ->get();

        return view('favorit', compact('favorites'));
    }

    public function toggle($menu_id)
    {
        $user = Auth::user();

        $exists = Favorite::where('user_id', $user->id)
            ->where('menu_id', $menu_id)
            ->first();

        if ($exists) {
            $exists->delete();
            $message = 'Dihapus dari favorit.';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'menu_id' => $menu_id
            ]);
            $message = 'Ditambahkan ke favorit!';
        }

        return redirect()->back()->with('success', $message);
    }
}
