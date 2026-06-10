<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nutrition extends Model
{
    protected $table = 'nutrition';

    protected $fillable = [
        'nutrition_name',
        'energy_kcal',
        'water',
        'fat',
        'total_fiber',
        'carbohydrate',
        'protein',
        'vitamin_c',
        'insoluable_fiber',
        'soluable_fiber',
        'biotin',
        'total_folates',
        'calcium',
        'cu',
        'fe',
        'mg',
        'p',
        'k',
        'se',
        'na',
        'za',
        'delete_status',
        'delete_by'
    ];
}
