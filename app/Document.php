<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Document extends Model
{
    //
    function revision() {
    	return $this->hasMany('App\Revision');
            }

    function type() {
    	return $this->belongsTo('App\Type');
    }

    function process() {
    	return $this->belongsTo('App\Process');
    }

    function customer() {
    	return $this->part();
    }

    function part() {
    	return $this->belongsTo('App\Part');
    }
 
 
}
