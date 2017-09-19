<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
	protected $fillable = ['name'];

    function revisions_files_types() {
        return $this->hasMany('App\revisions_files_types');
    }
}
