<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
	protected $fillable = ['name'];
    function document() {
    	return $this->belongsTo('App\Document');
    }
}
