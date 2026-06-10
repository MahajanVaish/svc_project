<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LhrFollowup extends Model
{
    use HasFactory;
    public $timestamps = false;



    protected $table = 'lhr_followups';


    protected $primaryKey = 'id';


    protected $keyType = 'int';


    public $incrementing = true;


    protected $fillable = [
        'patient_id',
        'branch_id',
        'branch',
        'patient_name',
        'address',
        'inquiry_date',
        'inquiry_time',
        'gender',
        'age',
        'area',
        'session',
        'afra_code',
        'energy',
        'frequency',
        'shot',
        'staff_name',
        'month_year',
        'refranceby',
        'next_follow_date',
        'notes',
        'payment_method',
        'total_payment',
        'discount_payment',
        'given_payment',
        'due_payment',
        'foc',
        'cash_price',
        'gpay_price',
        'cheque_price',
        'delete_status',
        'delete_by'
    ];

    /** 
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'foc' => 'boolean',
        'total_payment' => 'decimal:2',
        'discount_payment' => 'decimal:2',
        'given_payment' => 'decimal:2',
        'due_payment' => 'decimal:2',
        'cash_payment' => 'decimal:2',
        'google_pay' => 'decimal:2',
        'cheque_payment' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the inquiry that owns the follow up.
     */
    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(LHRInquiry::class , 'inquiry_id');
    }

    /**
     * Get formatted follow up date.
     */
    public function getFormattedFollowUpDateAttribute(): string
    {
        if (!$this->inquiry_date) {
            return 'N/A';
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $this->inquiry_date)->format('d/m/Y');
        }
        catch (\Exception $e) {
            return $this->inquiry_date;
        }
    }

    /**
     * Get formatted follow up time.
     */
    public function getFormattedFollowUpTimeAttribute(): string
    {
        if (!$this->inquiry_time) {
            return 'N/A';
        }

        try {
            $time = Carbon::createFromFormat('H:i:s', $this->inquiry_time);
            return $time->format('h:i A');
        }
        catch (\Exception $e) {
            return $this->inquiry_time;
        }
    }

    /**
     * Get formatted next follow up date.
     */
    public function getFormattedNextFollowUpDateAttribute(): string
    {
        if (!$this->next_follow_up_date) {
            return 'N/A';
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $this->next_follow_up_date)->format('d/m/Y');
        }
        catch (\Exception $e) {
            return $this->next_follow_up_date;
        }
    }

    /**
     * Get formatted total payment.
     */
    public function getFormattedTotalPaymentAttribute(): string
    {
        return '₹' . number_format(floatval($this->total_payment), 2);
    }

    /**
     * Get formatted due payment.
     */
    public function getFormattedDuePaymentAttribute(): string
    {
        return '₹' . number_format(floatval($this->due_payment), 2);
    }

    /**
     * Calculate due payment automatically.
     */
    public static function calculateDuePayment($total, $discount, $given): float
    {
        $due = (floatval($total) - floatval($discount)) - floatval($given);
        return max(0, $due);
    }

    /**
     * Scope a query to order by latest follow up.
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('inquiry_date', 'desc')
            ->orderBy('inquiry_time', 'desc');
    }

    /**
     * Scope a query to filter by inquiry.
     */
    public function scopeByInquiry($query, $inquiryId)
    {
        return $query->where('inquiry_id', $inquiryId);
    }

    /**
     * Scope a query to get upcoming follow ups.
     */
    public function scopeUpcoming($query, $days = 7)
    {
        $today = Carbon::today()->format('Y-m-d');
        $futureDate = Carbon::today()->addDays($days)->format('Y-m-d');

        return $query->where('next_follow_up_date', '>=', $today)
            ->where('next_follow_up_date', '<=', $futureDate)
            ->orderBy('next_follow_up_date', 'asc');
    }

    /**
     * Check if follow up is active (not deleted).
     */
    public function isActive(): bool
    {
        return $this->delete_status !== 'deleted';
    }
}