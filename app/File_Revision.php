<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File_Revision extends Model
{
    //
    public $table = "file_revision";

    protected $fillable = ['path'];
    public function revision(){
    	return $this->belongsTo('App\Revision');
    }
    public function file(){
    	return $this->belongsTo('App\File');
    }
}
