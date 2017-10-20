<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    //
	protected $fillable = ['part_number', 'material', 'description'];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function document()
    {
        return $this->hasMany('App\Part');
    }
  
}
