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
 
}
