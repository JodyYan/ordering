<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded=[];
    public function member()
    {
        return $this->belongsTo('App\Member', 'user_id');
    }
}
