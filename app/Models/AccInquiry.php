<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccInquiry extends Model
{
    use HasFactory;
    protected $table = 'acc_inquirys';
    protected $primaryKey = 'id';
    
    protected static function booted()
    {
        static::addGlobalScope('branch_restriction', function ($builder) {
            if (auth()->check()) {
                $user = auth()->user();
                // Superadmin and Doctor roles can see all data
                if (!$user->hasRole('Superadmin') && !$user->hasRole('Doctor')) {
                    $builder->where('acc_inquirys.branch_id', $user->user_branch);
                }
            }
        });
    }

    protected $fillable = [
        'patient_id',
        'branch',
        'branch_id',
        'patient_f_name',
        'patient_m_name',
        'patient_l_name',
        'gender',
        'phone_no',
        'age',
        'height',
        'weight',
        'bmi',
        'address',
        'refrance',
        'reference_to',
        'email',
        'inquiry_date',
        'inquiry_time',
        'inquery_given_by',
        'payment',
        'inquiry_foc',
        'complain',
        'diagnosis',
        'pod_vld_date',
        'user_status',
        'status_history',
        'client_old_new',
        'next_followup_date',
        'discount_payment',
        'delete_status',
        'delete_by',
        'is_online_abroad',
        // Direct single-table columns
        'diet',
        'exercise',
        'sleep',
        'water',
        'joined_program_ids',
        'programs_array',
        'cash_payment',
        'gpay_payment',
        'cheque_payment',
        'due_payment',
    ];

    protected $casts = [
        'branch_id' => 'string',
        // NOTE: status_history is intentionally NOT cast here.
        // A custom getStatusHistoryAttribute accessor handles decoding and always returns []
        // for null/invalid values. In Laravel 12, casts take priority over accessors and
        // would return null for null DB values, causing count(null) TypeError in PHP 8+.
    ];

    public function getInquiryDateAttribute($value)
    {
        if (!$value) return null;
        // Handle both Y-m-d (new) and d/m/Y (old legacy data)
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
            try {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            } catch (\Exception $e) {
                return $value;
            }
        }
        return $value;
    }

    public function getInquiryTimeAttribute($value)
    {
        if (!$value) return null;
        // Return H:i format, handle various stored formats
        try {
            return \Carbon\Carbon::parse($value)->format('H:i');
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function getStatusHistoryAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    public function setStatusHistoryAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['status_history'] = json_encode($value);
        } elseif (is_string($value)) {
            $this->attributes['status_history'] = $value;
        } else {
            $this->attributes['status_history'] = json_encode([]);
        }
    }

    public function getDisplayStatusesAttribute()
    {
        return $this->status_history ?? [];
    }

    public function hasStatus($status)
    {
        return in_array($status, $this->status_history ?? []);
    }

    public function hasAnyStatus(array $statuses)
    {
        return !empty(array_intersect($statuses, $this->status_history ?? []));
    }

    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('d/m/Y') : 'N/A';
    }

    public function getFormattedTimeAttribute()
    {
        return $this->time ? $this->time->format('H:i') : 'N/A';
    }

    public function getPatientNameAttribute()
    {
        return trim($this->patient_f_name . ' ' . $this->patient_m_name . ' ' . $this->patient_l_name);
    }

    public $timestamps = false;

}