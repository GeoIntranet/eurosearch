<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
   protected $connection ='yoda';
   protected $table='articles2';
   protected $fillable =['marque'];
}
