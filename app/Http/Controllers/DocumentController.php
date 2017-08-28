<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use App\Traits\dataTables;

class DocumentController extends Controller
{
    use dataTables;
    
        public function __construct()
    {
        $this->setControllerName('Document');
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
        $tableColumns = ['Revision', 'Description', 'Change', 'Date'];
        $dataColumns = ['revision', 'description', 'change_description', 'revision_date'];
        $columns = $this->addColumns($tableColumns, $dataColumns);
        $columns['url'] = action('DocumentController@tableData', $document);
        return view('document', $columns);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function tableData(Document $document)
    {
        //
        $revisions = Document::with('revision','type','part.customer', 'process')->find($document);
        $revision = $this->dataTablesData($revisions[0]['revision']);
        $revision['summary']['document_number'] = $revisions[0]['document_number'];
        $revision['summary']['customer'] = $revisions[0]['part']['customer']['name'];
        $revision['summary']['type'] = $revisions[0]['type']['name'];
        $revision['summary']['process'] = $revisions[0]['process']['name'];
        return ['data'=>$revision['data'], 'summary'=>$revision['summary']];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        //
    }
}
