<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyAssessment extends Model
{
    use HasFactory;

    protected $table = 'monthly_assessments';

    protected $fillable = [
        'branch_id',
        'patient_inquiry_id',
        'patient_id',
        'assessment_date',
        'status',

        // Measurements
        'waist_upper',
        'waist_middle',
        'waist_lower',
        'hips',
        'thighs',
        'arms',
        'waist_hips_ratio',
        'weight',
        'bmi',

        // BCA
        'bca_vbf',
        'bca_arms',
        'bca_trunk',
        'bca_legs',
        'bca_sf',
        'bca_vf',

        // Muscle
        'muscle_vbf',
        'muscle_arms',
        'muscle_trunk',
        'muscle_legs',

        // Additional
        'notes',
        'assessed_by',
        'diet',
        'exercise',
        'sleep',
        'water',

        // Delete status fields
        'delete_status',
        'delete_by',
        'delete_date',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'waist_upper' => 'float',
        'waist_middle' => 'float',
        'waist_lower' => 'float',
        'hips' => 'float',
        'thighs' => 'float',
        'arms' => 'float',
        'waist_hips_ratio' => 'float',
        'weight' => 'float',
        'bmi' => 'float',
        'bca_vbf' => 'float',
        'bca_arms' => 'float',
        'bca_trunk' => 'float',
        'bca_legs' => 'float',
        'bca_sf' => 'float',
        'bca_vf' => 'float',
        'muscle_vbf' => 'float',
        'muscle_arms' => 'float',
        'muscle_trunk' => 'float',
        'muscle_legs' => 'float',
        'delete_date' => 'datetime',
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function patient()
    {
        return $this->belongsTo(PatientInquiry::class, 'patient_inquiry_id');
    }

    // Scope for active records
    public function scopeActive($query)
    {
        return $query->where('delete_status', '0');
    }

    // Scope for deleted records
    public function scopeDeleted($query)
    {
        return $query->where('delete_status', '1');
    }
}
