<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded=[];

    public function flavors()
    {
        return $this->hasMany('App\Flavor');
    }
}
