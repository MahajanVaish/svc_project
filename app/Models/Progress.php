<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $table = 'progress_report';
    
    public $timestamps = false;
    
    protected $fillable = [
        'patient_id',
        'branch_name',
        'branch_id',
        'patient_name',
        'date',
        'time',
        'body_part',
        'bp_p',
        'pulse',
        'detox',
        'face_program',
        'relaxation',
        'lypolysis_treatment',
        'weight',
        'height',
        'bmi',
        'councilor_doctor',
        'exercise',
        'diet',
        'sleep',
        'water',
        'medication',
        'delete_status',
        'delete_by',
    ];
    
    protected $casts = [
        'date' => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope('branch_restriction', function ($builder) {
            if (auth()->check()) {
                $user = auth()->user();
                if (!$user->hasRole('Superadmin') && !$user->hasRole('Doctor')) {
                    $builder->where('progress_report.branch_id', $user->user_branch);
                }
            }
        });
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->delete_by = $model->delete_by ?? 0;
            $model->delete_status = $model->delete_status ?? '0';
        });
    }
    
    public function patient()
    {
        return $this->belongsTo(PatientInquiry::class, 'patient_id', 'patient_id');
    }
    
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }
}