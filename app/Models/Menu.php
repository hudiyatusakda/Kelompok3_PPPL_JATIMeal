<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $fillable = [
        'nama_menu',
        'kategori',
        'bahan_baku',
        'harga_bahan',
        'referensi',
        'gambar',
        'deskripsi',
        'user_id',
        'menu_id',
        'isi_komentar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
