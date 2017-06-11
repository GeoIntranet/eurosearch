<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{

    protected $fillable=['data','count_result'];
    protected $table='searchs';
    protected $dates=['created_at'];

}
