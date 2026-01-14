<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyPlan extends Model
{
    protected $fillable = ['user_id', 'menu_id', 'planned_date', 'week','month', 'year', 'day_of_week', 'day', 'is_completed'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
