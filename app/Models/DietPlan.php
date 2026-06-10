<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'patient_id',
        'patient_name',
        'date',
        'diet_name',
        'time_search_menus',
        'general_notes',
        'next_follow_up_date',
        'created_by',
        'diet',
        'exercise',
        'sleep',
        'water'
    ];

    protected $casts = [
        'date' => 'date',
        'next_follow_up_date' => 'date',
        'time_search_menus' => 'array'
    ];

    /**
     * Get the user who created the diet plan
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get patient details.
     * patient_id column stores the string ID (e.g. "SVC-00001"), so we join
     * on patient_inquiry.patient_id (not the numeric primary key).
     */
    public function patient()
    {
        return $this->belongsTo(PatientInquiry::class, 'patient_id', 'patient_id');
    }

    /**
     * Get branch details
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }
}