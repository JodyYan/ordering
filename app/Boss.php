<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Boss extends Model
{
    protected $guarded=[];
    protected $hidden=['password'];
}
