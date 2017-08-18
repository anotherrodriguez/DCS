<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    //
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function document()
    {
        return $this->hasMany('App\Part');
    }
  
}
