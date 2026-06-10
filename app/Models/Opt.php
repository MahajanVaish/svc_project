<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opt extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'patient_name',
        'branch_id',
        'branch',
        'blood_group',
        'patient_relation',
        'delete_status',
        'delete_by',
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
     * Get the meta data for the opt.
     */
    public function meta(): HasMany
    {
        return $this->hasMany(OptMeta::class);
    }

    /**
     * Get a specific meta value by key.
     *
     * @param string $key
     * @return mixed
     */
    public function getMetaValue(string $key)
    {
        $meta = $this->meta()->where('meta_key', $key)->first();
        return $meta ? $meta->meta_value : null;
    }

    /**
     * Set a meta value for the opt.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setMetaValue(string $key, $value): void
    {
        $this->meta()->updateOrCreate(
            ['meta_key' => $key],
            ['meta_value' => $value]
        );
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
