<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Followups extends Model
{
    // use SoftDeletes;
    protected $table = 'patient_followups';

    protected $fillable = [
        'patient_id',
        'inquiry_id',
        'followup_date',
        'next_follow_date',
        'delete_status',
        'delete_by',
        'doctor_id',
        'zoom_meeting_id',
        'zoom_start_url',
        'zoom_join_url',
        'zoom_password',
    ];

    protected $casts = [
        'followup_date' => 'date',
        'next_follow_date' => 'date',
    ];

    // Add accessor for patient_name
    public function getPatientNameAttribute()
    {
        return $this->inquiry ? $this->inquiry->patient_name : 'N/A';
    }

    public function treatments()
    {
        return $this->hasMany(PatientTreatment::class, 'followup_id');
    }

    public function inquiry()
    {
        return $this->belongsTo(PatientInquiry::class, 'inquiry_id');
    }

    // Alias for backward compatibility
    public function inquiryPatient()
    {
        return $this->inquiry();
    }

    public function metas()
    {
        return $this->hasMany(FollowupMeta::class, 'followup_id');
    }

    // Helper method
    public function setMeta($key, $value)
    {
        $this->metas()->updateOrCreate(
            ['meta_key' => $key],
            ['meta_value' => is_string($value) ? $value : json_encode($value)]
        );
    }

    // Get meta value
    public function getMeta($key)
    {
        $meta = $this->metas()->where('meta_key', $key)->first();
        return $meta ? $meta->meta_value : null;
    }
    // In Followups model
    public function getFollowupsTimeAttribute()
    {
        $timeMeta = $this->metas->firstWhere('meta_key', 'followups_time');
        return $timeMeta ? $timeMeta->meta_value : '00:00:00';
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}