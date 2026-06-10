<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PatientInquiry;
use App\Models\Invoice;
use App\Models\ManageProgram;

class PatientTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'patient_id',
        'invoice_id',
        'program_id',
        'type',
        'amount',
        'description',
        'created_at'
    ];

    public function patient()
    {
        return $this->belongsTo(PatientInquiry::class, 'patient_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function program()
    {
        return $this->belongsTo(ManageProgram::class, 'program_id');
    }

    /**
     * Resolve the patient from the correct table based on the invoice's branch
     */
    public function getResolvedPatientAttribute()
    {
        if ($this->invoice) {
            return $this->invoice->resolved_patient;
        }

        // Fallback if no invoice exists (less likely for patient transactions but good to have)
        return PatientInquiry::find($this->patient_id);
    }
}
