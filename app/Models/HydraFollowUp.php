<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HydraFollowUp extends Model
{
    use HasFactory;

    protected $table = 'hydra_patient_followups'; // नया table name

    protected $fillable = [
        'hydra_inquiry_id',
        'follow_up_date',
        'follow_up_time',
        'patient_name',
        'gender',
        'age',
        'next_follow_up_date',
        'foc',
        'total_payment',
        'discount_payment',
        'given_payment',
        'due_payment',
        'cash_payment',
        'google_pay',
        'phone_number',
        'session',
        'address',
        'notes'
    ];

    protected $casts = [
        'follow_up_date' => 'date',
        'next_follow_up_date' => 'date',
        'foc' => 'boolean',
        'total_payment' => 'decimal:2',
        'discount_payment' => 'decimal:2',
        'given_payment' => 'decimal:2',
        'due_payment' => 'decimal:2',
        'cash_payment' => 'decimal:2',
        'google_pay' => 'decimal:2',
        'follow_up_time' => 'datetime:H:i',
    ];

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(HydraInquiry::class, 'hydra_inquiry_id');
    }
}