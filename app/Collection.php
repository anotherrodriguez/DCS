<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    //
    function process() {
    	return $this->belongsTo('App\Process');
    }
    
    function collection_document() {
        return $this->hasMany('App\collection_document');
    }
}
