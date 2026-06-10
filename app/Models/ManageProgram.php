<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class ManageProgram extends Model
{
    use HasFactory;
 
    protected $table = 'manage_programs';
 
    protected $fillable = [
        'program_name',
        'program_short_name',
        'gender',
        'branch',
        'program_price',
        'profram_log',
        'delete_status',
        'delete_by'
    ];
 
    protected $casts = [
        'program_price' => 'decimal:2',
        'delete_status' => 'boolean'
    ];
}