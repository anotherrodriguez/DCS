<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Revision extends Model
{
    //
    protected $fillable = ['description', 'revision_date', 'revision', 'change_description'];

    function document() {
    	return $this->belongsTo('App\Document');
    }

    function user() {
        return $this->belongsTo('App\User');
    }

    function latestRevision() {
    		return $this
            ->join(DB::raw("(SELECT `document_id`, MAX(`revision_date`) as `rev_date` FROM `revisions` GROUP BY `document_id`) AS `max_rev`"), function($join){
                $join->on('max_rev.document_id', '=', 'revisions.document_id')->on('max_rev.rev_date', '=', 'revisions.revision_date');
            });

    }

    function file_revision() {
        return $this->hasMany('App\File_Revision');
    }

    function file() {
        return $this->hasMany('App\File');
    }    

}
