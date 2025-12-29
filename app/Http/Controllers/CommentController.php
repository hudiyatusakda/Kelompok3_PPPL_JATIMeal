<?php

namespace App\Models;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $menu_id)
    {
        $request->validate([
            'isi_komentar' => 'required|string|max:500',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'menu_id' => $menu_id,
            'isi_komentar' => $request->isi_komentar
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}
