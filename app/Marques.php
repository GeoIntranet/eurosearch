<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marques extends Model
{
    public $timestamps = false;
    protected $table='marques';
    protected $fillable=['marque'];
}
