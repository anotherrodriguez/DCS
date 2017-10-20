<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class collection_document extends Model
{
    //
    function collection() {
    	return $this->belongsTo('App\Collection');
    }
    function document() {
    	return $this->belongsTo('App\Document');
    }
}
