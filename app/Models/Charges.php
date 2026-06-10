<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charges extends Model
{
    use SoftDeletes;
     protected $table = 'charges';


    protected $fillable = ['charges_name','charges_price','delete_status','delete_by'];
}
    