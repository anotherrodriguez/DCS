<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ECN extends Model
{
    //
	protected $table = "ecns";

	protected $fillable = ['operator','part_number','sequence_number','change_request','notes','status'];

    function collection() {
    	return $this->belongsTo('App\Collection');
    }
    
    function user() {
    	return $this->belongsTo('App\User');
    }

    function status() {
    	return $this->belongsTo('App\ecn_status');
    }

    function change_request() {
    	return $this->belongsTo('App\change_request');
    }
}
