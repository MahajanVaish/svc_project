<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientMeta extends Model
{
    protected $table = 'patients_metas';
    protected $fillable = ['patient_id', 'meta_key', 'meta_value'];


       public function patient()
    {
        return $this->belongsTo(PatientInquiry::class, 'patient_id');
    }
}
