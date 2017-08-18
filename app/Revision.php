<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Revision extends Model
{
    //
    function document() {
    	return $this->belongsTo('App\Document');
    }

    function latestRevision () {
    		return $this
            ->join(DB::raw("(SELECT `document_id`, MAX(`revision_date`) as `rev_date` FROM `revisions` GROUP BY `document_id`) AS `max_rev`"), function($join){
                $join->on('max_rev.document_id', '=', 'revisions.document_id')->on('max_rev.rev_date', '=', 'revisions.revision_date');
            })
            ->join('documents', 'documents.id', '=', 'revisions.document_id')
            ->join('types', 'types.id', '=', 'documents.type_id')
            ->join('parts', 'parts.id', '=', 'documents.part_id')
            ->join('processes', 'processes.id', '=', 'documents.process_id')
            ->join('customers', 'customers.id', '=', 'parts.customer_id')
            ->select('revisions.*', 'types.name as type', 'documents.document_number', 'parts.part_number', 'processes.name as process', 'customers.name as customer');

    }
}
