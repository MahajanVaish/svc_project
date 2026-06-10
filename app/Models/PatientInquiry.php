<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientInquiry extends Model
{
    use SoftDeletes;
    protected $table = 'patient_inquiry';

    protected $casts = [
        'inquiry_date' => 'datetime',
        'next_follow_date' => 'date',
    ];


    protected $fillable = [
        'patient_id',
        'branch',
        'branch_id',
        'patient_name',
        'address',
        'age',
        'diagnosis',
        'inquiry_date',
        'next_follow_date',
        'deleted_at',
        'delete_status'

    ];

    public function metas()
    {
        return $this->hasMany(PatientMeta::class,   'patient_id');
    }

    public function setMeta($key, $value)
    {
        $this->metas()->updateOrCreate(
            ['meta_key' => $key],
            ['meta_value' => is_string($value) ? $value : json_encode($value)]
        );
    }

    public function getMeta($key)
    {
        $value = optional($this->metas->where('meta_key', $key)->first())->meta_value;
        return $value === 'null' ? null : $value;
    }

    public function getAllMeta()
    {
        $meta = [];
        foreach ($this->metas as $m) {
            $decoded = json_decode($m->meta_value, true);
            $meta[$m->meta_key] = json_last_error() === JSON_ERROR_NONE ? $decoded : $m->meta_value;
        }
        return $meta;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($patient) {

            if ($patient->isForceDeleting()) {
                $patient->metas()->forceDelete();
            } else {
                $patient->metas()->delete();
            }
        });
    }


    public function followups()
    {
        return $this->hasMany(Followups::class, 'inquiry_id', 'id');
    }

    public function treatments()
    {
        return $this->hasMany(PatientTreatment::class, 'patient_id', 'patient_id');
    }
     public function invoice()
    {
        return $this->hasOne(Invoice::class, 'patient_id', 'id');
    }
}
