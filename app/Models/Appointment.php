<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'patient_id',
        'patient_name',
        'phone',
        'appointment_date',
        'appointment_time',
        'content',
        'branch_id',
        'status',
        'created_by'
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];
}
