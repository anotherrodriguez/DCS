<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Document extends Model
{
    //
    protected $fillable = ['document_number'];

    function revision() {
    	return $this->hasMany('App\Revision');
    }

    function type() {
    	return $this->belongsTo('App\Type');
    }

    function process() {
    	return $this->belongsTo('App\Process');
    }

    function customer() {
    	return $this->part();
    }

    function part() {
    	return $this->belongsTo('App\Part');
    }

    function latestRevision () {
        return $this
            ->join(DB::raw("(SELECT `document_id`, MAX(`revision_date`) as `rev_date` FROM `revisions` GROUP BY `document_id`) AS `max_rev`"), function($join){
                $join->on('max_rev.document_id', '=', 'documents.id');
            })->join('revisions', function($join){
                $join->on('documents.id', '=', 'revisions.document_id')->on('max_rev.rev_date', '=', 'revisions.revision_date');
            })->select('documents.*','revisions.revision', 'revisions.id as revision_id','revisions.description');
    }

    function collection_document() {
        return $this->hasMany('App\collection_document');
    }
 
 
}
