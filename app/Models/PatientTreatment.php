<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientTreatment extends Model
{

    protected $table = 'patient_medicine_treatments';
    protected $fillable = [
        'patient_id',
        'inquiry_id',
        'followup_id',
        'type',
        'medicine',
        'dose',
        'timing',
        'days',
        'date',
        'time',
        'note',
    ];

    public function inquiry()
    {
        return $this->belongsTo(PatientInquiry::class, 'patient_id', 'patient_id');
    }
    public function followup()
    {
        return $this->belongsTo(Followups::class, 'followup_id', 'id');
    }
}
