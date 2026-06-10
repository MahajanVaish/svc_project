<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programs extends Model
{
    protected $table = 'progress_report';

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
        'breast_reshaping',
        'face_program',
        'relaxation',
        'lypolysis_treatment',
        'weight',
        'councilor_doctor',
        'exercise',
        'delete_status',
        'delete_by',
    ];
}
