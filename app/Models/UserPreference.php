<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id',
        'protein_preference',
        'price_range',
        'cooking_style',
        'has_side_dish',
        'has_vegetables'
    ];
}
