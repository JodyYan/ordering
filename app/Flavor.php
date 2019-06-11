<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flavor extends Model
{
    protected $guarded=[];
    public function menu()
    {
        return $this->belongTo('App\Menu');
    }
}
