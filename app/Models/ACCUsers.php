<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class ACCUsers extends Authenticatable
{
    use HasRoles;
    protected $guard_name = 'acc';

    protected $table = 'acc_users_list';

    protected $fillable = [
        'user_name',
        'email',
        'password',
        'user_role',
        'user_branch',  
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // App/Models/ACCUsers.php
public function branch()
{
    return $this->belongsTo(Branch::class, 'user_branch', 'branch_id');
}

}
