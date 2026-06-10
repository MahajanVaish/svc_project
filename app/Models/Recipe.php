<?php
// Recipe model
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image'];

    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }
}