<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OptMeta extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opt_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'opt_id',
        'meta_key',
        'meta_value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the opt that owns the meta data.
     */
    public function opt(): BelongsTo
    {
        return $this->belongsTo(Opt::class);
    }
    public function getBeforePicture1Attribute()
{
    return $this->getMetaValue('before_picture_1');
}

public function getAfterPicture1Attribute()
{
    return $this->getMetaValue('after_picture_1');
}

}