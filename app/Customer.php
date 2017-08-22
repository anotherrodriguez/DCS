<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	protected $fillable = ['name'];
    //
    public function parts()
    {
        return $this->hasMany('App\Part');
    }
}
