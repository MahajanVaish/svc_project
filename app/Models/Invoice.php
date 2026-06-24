<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AccInquiry;
use App\Models\Opt;
use App\Models\OptMeta;

class Invoice extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope('branch_restriction', function ($builder) {
            if (auth()->check()) {
                $user = auth()->user();
                if (!$user->hasRole('Superadmin') && !$user->hasRole('Doctor')) {
                    $builder->where('invoices.branch_id', $user->user_branch);
                }
            }
        });
    }

    protected $fillable = [
        'branch_id',
        'patient_id',
        'program_id',
        'invoice_no',
        'invoice_date',
        'address',
        'phone',
        'price',
        'pending_due',
        'total_payment',
        'discount',
        'given_payment',
        'due_payment',
        'invoice_file',
        'charges_data',
        'programs_data',
        'cash_payment',
        'gpay_payment',
        'cheque_payment'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'price' => 'float',
        'pending_due' => 'float',
        'total_payment' => 'float',
        'discount' => 'float',
        'given_payment' => 'float',
        'due_payment' => 'float',
        'charges_data' => 'array',
        'programs_data' => 'array',
        'cash_payment' => 'float',
        'gpay_payment' => 'float',
        'cheque_payment' => 'float',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(PatientInquiry::class, 'patient_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function program()
    {
        return $this->belongsTo(ManageProgram::class, 'program_id');
    }

    /**
     * Resolve the patient from the correct table based on branch
     */
    public function getResolvedPatientAttribute()
    {
        $branchId = $this->branch_id;

        // LHR Branch
        if ($branchId === 'LB-0007') {
            return LHRInquiry::find($this->patient_id);
        }

        // Hydra Branch
        if ($branchId === 'BH-00023') {
            return HydraInquiry::find($this->patient_id);
        }

        // SVC Branch
        if ($branchId === 'SVC-0005') {
            // First check PatientInquiry by database ID, then by patient_id field
            $patient = PatientInquiry::find($this->patient_id);
            if ($patient) return $patient;
            
            // Fallback: try searching by patient_id field
            $patient = PatientInquiry::where('patient_id', $this->patient_id)->first();
            if ($patient) return $patient;
            
            $acc = AccInquiry::find($this->patient_id);
            if ($acc) {
                // Map patient_f_name for consistency
                $acc->patient_name = $acc->patient_f_name . ' ' . $acc->patient_l_name;
                return $acc;
            }
        }

        // FNF PP Branch (PP-0002) — patients stored in acc_inquirys
        if ($branchId === 'PP-0002') {
            $acc = AccInquiry::withoutGlobalScope('branch_restriction')->find($this->patient_id);
            if ($acc) {
                $acc->patient_name = $acc->patient_f_name . ' ' . $acc->patient_m_name . ' ' . $acc->patient_l_name;
                $acc->patient_name = trim($acc->patient_name);
                return $acc;
            }
        }

        // General Fallback
        $acc = AccInquiry::withoutGlobalScope('branch_restriction')->find($this->patient_id);
        if ($acc) {
            $acc->patient_name = $acc->patient_f_name . ' ' . $acc->patient_l_name;
            return $acc;
        }

        // Last resort: try PatientInquiry by patient_id field
        return PatientInquiry::where('patient_id', $this->patient_id)->first();
    }

    public function getTotalDueAttribute()
    {
        return $this->due_payment;
    }

    /**
     * Retrieve the online/abroad program label for this invoice.
     * Falls back to OptMeta program_name / online_program_label when programs_data is null.
     */
    public function getOnlineProgramLabelAttribute(): ?string
    {
        // If programs_data already has data, return first item name
        if (!empty($this->programs_data) && is_array($this->programs_data)) {
            $first = $this->programs_data[0] ?? null;
            return $first['program_name'] ?? null;
        }

        // Fallback: look up from OptMeta via the AccInquiry patient (bypass scope for cross-branch resolution)
        $patient = AccInquiry::withoutGlobalScope('branch_restriction')->find($this->patient_id);
        if (!$patient) return null;

        $optIds = \App\Models\Opt::where('patient_id', $patient->patient_id)->pluck('id');
        if ($optIds->isEmpty()) return null;

        // Try online_program_label first, then program_name
        $label = OptMeta::whereIn('opt_id', $optIds)
            ->where('meta_key', 'online_program_label')
            ->orderByDesc('id')
            ->value('meta_value');

        if (!$label) {
            $label = OptMeta::whereIn('opt_id', $optIds)
                ->where('meta_key', 'program_name')
                ->orderByDesc('id')
                ->value('meta_value');
        }

        return $label ?: null;
    }
}


