<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowupMeta extends Model
{
    protected $table = 'followup_meta';

    protected $fillable = [
        'followup_id',
        'meta_key',
        'meta_value',
    ];
    
    public $timestamps = false;
    
    public function followup()
    {
        return $this->belongsTo(Followups::class, 'followup_id');
    }
}