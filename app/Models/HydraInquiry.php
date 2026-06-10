<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HydraInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'branch',
        'branch_id',
        'patient_name',
        'inquiry_date',
        'address',
        'profile_image',
        'inquiry_time',
        'phone_number',
        'gender',
        'age',
        'reference_by',
        'session',
        'next_follow_up',
        'foc',
        'total_payment',
        'discount_payment',
        'given_payment',
        'due_payment',
        'cash_payment',
        'google_pay',
        'payment_mode',
        'status_name',
        'diet',
        'exercise',
        'sleep',
        'water',
    ];

    protected $casts = [
        'foc' => 'boolean',
        'total_payment' => 'decimal:2',
        'discount_payment' => 'decimal:2',
        'given_payment' => 'decimal:2',
        'due_payment' => 'decimal:2',
        'cash_payment' => 'decimal:2',
        'google_pay' => 'decimal:2',
        'inquiry_date' => 'date',
        'next_follow_up' => 'date',
        'inquiry_time' => 'datetime'
    ];

    /**
     * Get all follow ups for the inquiry.
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(HydraFollowUp::class, 'hydra_inquiry_id');
    }

    /**
     * Get the latest follow up.
     */
    public function latestFollowUp()
    {
        return $this->hasOne(HydraFollowUp::class, 'hydra_inquiry_id')->latestOfMany();
    }

    /**
     * Scope a query to order follow ups by latest first.
     */
    public function scopeWithLatestFollowUps($query)
    {
        return $query->with(['followUps' => function ($query) {
            $query->orderBy('follow_up_date', 'desc')
                  ->orderBy('follow_up_time', 'desc');
        }]);
    }

    /**
     * Get formatted inquiry date.
     */
    public function getFormattedInquiryDateAttribute(): string
    {
        return $this->inquiry_date ? $this->inquiry_date->format('d/m/Y') : 'N/A';
    }

    /**
     * Get formatted inquiry time.
     */
    public function getFormattedInquiryTimeAttribute(): string
    {
        if (!$this->inquiry_time) {
            return 'N/A';
        }
        
        $time = \Carbon\Carbon::parse($this->inquiry_time);
        return $time->format('h:i A');
    }

    /**
     * Get formatted next follow up date.
     */
    public function getFormattedNextFollowUpAttribute(): string
    {
        return $this->next_follow_up ? $this->next_follow_up->format('d/m/Y') : 'N/A';
    }

    /**
     * Get patient ID with prefix.
     */
    // public function getPatientIdAttribute(): string
    // {
    //     return 'HYDRA-' . str_pad($this->id, 7, '0', STR_PAD_LEFT);
    // }
}
