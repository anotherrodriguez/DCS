<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    //
    protected $fillable = ['description'];

    function process() {
    	return $this->belongsTo('App\Process');
    }
    
    function collection_document() {
        return $this->hasMany('App\collection_document');
    }

    function user() {
        return $this->belongsTo('App\User');
    }
    
    function ecn() {
        return $this->hasMany('App\ECN');
    }
}
