<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
	protected $fillable = ['name'];

	function file_revision() {
        return $this->hasMany('App\File_Revision');
    }

	function revision() {
        return $this->belongsTo('App\Revision');
    }
}
