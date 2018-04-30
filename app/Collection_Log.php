<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Collection_Log extends Model
{
    //
	protected $table = "collection_log";

    protected $fillable = ['document_change'];

    function collection() {
    	return $this->belongsTo('App\Collection');
    }
    function document() {
    	return $this->belongsTo('App\Document');
    }
    function user() {
        return $this->belongsTo('App\User');
    }
    function lastRevision () {
        return $this
            ->join(DB::raw("(SELECT `document_id`, MAX(`revision_date`) as `rev_date` FROM `revisions` GROUP BY `document_id`) AS `max_rev`"), function($join){
                $join->on('max_rev.document_id', '=', 'documents.id');
            })->join('revisions', function($join){
                $join->on('documents.id', '=', 'revisions.document_id')->on('max_rev.rev_date', '=', 'revisions.revision_date');
            })->select('documents.*','revisions.revision', 'revisions.id as revision_id','revisions.description');
    }

}
