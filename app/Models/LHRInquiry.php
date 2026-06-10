<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LHRInquiry extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lhr_inquiries';

    protected $fillable = [
        'patient_id',
        'branch',
        'branch_id',
        'patient_name',
        'inquiry_date',
        'address',
        'profile_image',
        'mobile_no',
        'email',

        // Gender & Basic Info
        'gender',
        'age',
        'year',
        'area',
        'session',
        'area_code',
        'energy',
        'frequency',
        'shot',
        'staff_name',
        'status_name',

        // Medical Questions
        'hormonal_issues',
        'medication',
        'previous_treatment',
        'pcod_thyroid',
        'skin_conditions',
        'ongoing_treatments',
        'implants_tattoos',
        'procedure',

        // Reference Information
        'reference_by',
        'next_follow_up',
        'notes',

        // Payment Information
        'foc',
        'total_payment',
        'discount_payment',
        'given_payment',
        'due_payment',
        'payment_method',
        'payment_amount',

        // File Paths
        'before_picture_1',
        'before_picture_2',
        'before_picture_3',
        'before_picture_4',
        'before_picture_5',
        'after_picture_1',
        'after_picture_2',
        'after_picture_3',
        'after_picture_4',
        'after_picture_5',

        // Account and Time
        'account',
        'time',
        'diet',
        'exercise',
        'sleep',
        'water',
    ];

    protected $casts = [
        'inquiry_date' => 'date',
        'next_follow_up' => 'date',
        'time' => 'datetime:H:i:s',
        'foc' => 'boolean',
        'total_payment' => 'decimal:2',
        'discount_payment' => 'decimal:2',
        'given_payment' => 'decimal:2',
        'due_payment' => 'decimal:2',
        'cash_payment' => 'decimal:2',
        'google_pay' => 'decimal:2',
        'cheque_payment' => 'decimal:2',
    ];

    /**
     * Get yes/no value in readable format
     */
    public function getYesNoValue($value)
    {
        if ($value === 'yes') {
            return 'Yes';
        } elseif ($value === 'no') {
            return 'No';
        }
        return $value ?? 'Not specified';
    }

    /**
     * Scope for pending inquiries
     */
    public function scopePending($query)
    {
        return $query->where('status_name', 'pending');
    }

    /**
     * Scope for joined inquiries
     */
    public function scopeJoined($query)
    {
        return $query->where('status_name', 'joined');
    }

    /**
     * Scope for global search
     */
    public function scopeGlobalSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('patient_id', 'like', "%{$search}%")
                ->orWhere('patient_name', 'like', "%{$search}%")
                ->orWhere('gender', 'like', "%{$search}%")
                ->orWhere('age', 'like', "%{$search}%")
                ->orWhere('area', 'like', "%{$search}%")
                ->orWhere('branch', 'like', "%{$search}%")
                ->orWhere('branch_id', 'like', "%{$search}%")
                ->orWhere('status_name', 'like', "%{$search}%")
                ->orWhereIn('patient_id', function($subquery) use ($search) {
                    $subquery->select('opts.patient_id')
                             ->from('opts')
                             ->join('opt_meta', 'opts.id', '=', 'opt_meta.opt_id')
                             ->where(function($qq) use ($search) {
                                 $qq->where(function($qqq) use ($search) {
                                     $qqq->where('meta_key', 'selected_program')
                                         ->where('meta_value', 'like', '%' . $search . '%');
                                 })
                                 ->orWhere(function($qqq) use ($search) {
                                     $qqq->where('meta_key', 'programs_array')
                                         ->where('meta_value', 'like', '%' . $search . '%');
                                 });
                             });
                });
        });
    }

    /**
     * Get the current procedure in readable format
     */
    public function getCurrentProcedureTextAttribute()
    {
        $procedures = [
            'waxing' => 'Waxing',
            'threading' => 'Threading',
            'cream' => 'Hair Removal Cream',
            'shaving' => 'Shaving',
            'laser' => 'Laser Hair Removal',
            'ipl' => 'IPL',
            'electrolysis' => 'Electrolysis',
        ];

        if ($this->current_procedure && isset($procedures[$this->current_procedure])) {
            return $procedures[$this->current_procedure];
        }

        return $this->current_procedure ?? 'Not specified';
    }
}
