<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class collection_document extends Model
{
    //
    function collection() {
    	return $this->belongsToMany('App\Collection');
    }
    function document() {
    	return $this->belongsToMany('App\Document');
    }
}
