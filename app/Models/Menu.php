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
    ];
}
