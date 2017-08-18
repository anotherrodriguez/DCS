<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
    function document() {
    	return $this->belongsTo('App\Document');
    }
}
