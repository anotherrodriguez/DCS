<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class revisions_files_types extends Model
{
    //
    protected $fillable = ['file_path'];

    public function revision(){
    	return $this->belongsTo('App\Revision');
    }

    public function type(){
    	return $this->belongsTo('App\Type');
    }
}
