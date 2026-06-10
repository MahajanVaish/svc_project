<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'inquiry_id',
        'branch_id',
        'branch',
        'user_id',
        'call_date',
        'call_time',
        'time_slot',
        'remarks',
        'diet',
        'exercise',
        'sleep',
        'water',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function inquiry()
    {
        return $this->belongsTo(AccInquiry::class, 'inquiry_id');
    }
}
