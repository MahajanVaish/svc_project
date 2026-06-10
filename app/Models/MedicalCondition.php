<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get complaints only
     */
    public static function getComplaints()
    {
        return self::where('type', 'complaint')->where('is_active', true)->orderBy('name')->get();
    }

    /**
     * Get diagnoses only
     */
    public static function getDiagnoses()
    {
        return self::where('type', 'diagnosis')->where('is_active', true)->orderBy('name')->get();
    }

    /**
     * Get all active conditions
     */
    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('type')->orderBy('name')->get();
    }

    /**
     * Check if condition exists
     */
    public static function exists($name, $type)
    {
        return self::where('name', $name)->where('type', $type)->where('is_active', true)->exists();
    }

    /**
     * Add new condition if it doesn't exist
     */
    public static function addIfNotExists($name, $type)
    {
        if (!self::exists($name, $type)) {
            return self::create([
                'name' => $name,
                'type' => $type,
                'is_active' => true
            ]);
        }
        return self::where('name', $name)->where('type', $type)->first();
    }

    /**
     * Scope to get only complaints
     */
    public function scopeComplaints($query)
    {
        return $query->where('type', 'complaint');
    }

    /**
     * Scope to get only diagnoses
     */
    public function scopeDiagnoses($query)
    {
        return $query->where('type', 'diagnosis');
    }

    /**
     * Scope to get only active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
