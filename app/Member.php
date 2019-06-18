<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $guarded=[];
    protected $hidden=['password'];

    public function group()
    {
        return $this->belongsTo('App\Group');
    }
}
