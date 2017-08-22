<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    $tableColumns = ['Document', 'Revision', 'Description', 'Customer'];
    $dataColumns = ['document.document_number', 'revision', 'description', 'document.part.customer.name'];
    if (Auth::check()) {
            // The user is logged in...
            $dataColumns[] = 'edit';
            $tableColumns[] = 'edit';    
            }
    $url = action('RevisionController@revisionData');
    $createUrl = action('DocumentController@create');
    $title = 'Documents';
    $columns = ['tableColumns' => $tableColumns, 'dataColumns' => $dataColumns, 'url' => $url, 'title' => $title, 'createUrl' => $createUrl];
    return view('dataTable', $columns);
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
        $url = action('DocumentController@documentData', $document);
        $title = 'Revision History';
        $columns = ['tableColumns' => $tableColumns, 'dataColumns' => $dataColumns, 'url' => $url, 'title' => $title];
        return view('document', $columns);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function documentData(Document $document)
    {
        //
        $revisions = Document::with('revision','type','part.customer', 'process')->find($document);
        $revision['data'] = $revisions[0]['revision'];
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
