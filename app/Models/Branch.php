<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    // use SoftDeletes;

    protected $table = 'branches';
    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('branch_restriction', function ($builder) {
            if (auth()->check()) {
                $user = auth()->user();
                if (!$user->hasRole('Superadmin') && !$user->hasRole('Doctor')) {
                    $builder->where('branches.branch_id', $user->user_branch);
                }
            }
        });
    }

    protected $fillable = [
        'branch_id',
        'branch_name',
        'show_branch',
        'address',
        'delete_status',
        'delete_by'
    ];

    // Add this relationship if you need to access invoices
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'branch_id', 'branch_id');
    }


}
