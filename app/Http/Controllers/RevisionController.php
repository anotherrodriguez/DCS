<?php

namespace App\Http\Controllers;

use App\Revision;
use Illuminate\Http\Request;
use App\Traits\dataTables;
use Illuminate\Support\Facades\Auth;

class RevisionController extends Controller
{
    use dataTables;
    
        public function __construct()
    {
        $this->setControllerName('Revision');
    }
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
       return $this->dataTablesIndex($tableColumns, $dataColumns);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tableData()
    {
        //
        $revisions = new Revision();
        $revisions = $revisions->latestRevision()->with('document.part.customer','document.process','document.type')->get();
        return $this->dataTablesData($revisions);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($part_id)
    {
        //
        $part =\App\Part::find($part_id);
        $types = \App\Type::all();
        $processes = \App\Process::all();
        $data = ['part'=>$part, 'types'=>$types, 'processes'=>$processes];
        return view('forms.revision', $data);
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
        $part = \App\Part::find($request->get('part_id'));
        $type = \App\Type::find($request->get('type_id'));
        $process = \App\Process::find($request->get('process_id'));

        $document = new \App\Document([
            'document_number' =>  $request->get('document_number')
            ]);

        $document->part()->associate($part);
        $document->type()->associate($type);
        $document->process()->associate($process);

        $document->save();

        
        $revision = new Revision([
            'description' => $request->get('description'),
            'revision_date' => $request->get('revision_date'),
            'revision' => $request->get('revision'),
            'change_description' => $request->get('change_description')
        ]);
        
        $revision->document()->associate($document);

        $revision->user()->associate(Auth::user());
        
        $revision->save();
        
        return redirect('revisions')->with('status', 'success')->with('message', 'Revision "'.$revision->revision.'" was added successfully to document "'.$document->document_number.'".');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Revision  $revision
     * @return \Illuminate\Http\Response
     */
    public function show(Revision $revision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Revision  $revision
     * @return \Illuminate\Http\Response
     */
    public function edit(Revision $revision)
    {
        //
        $revision = $revision::with('document','document.part','document.part.customer','document.type','document.process')->find($revision->id);
        $types = \App\Type::all();
        $processes = \App\Process::all();
        $data = ['revision'=>$revision, 'types'=>$types, 'processes'=>$processes];

        return view('forms.revisionEdit', $data);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Revision  $revision
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Revision $revision)
    {
        //
        $type = \App\Type::find($request->get('type_id'));
        $process = \App\Process::find($request->get('process_id'));
        $document = \App\Document::find($request->get('document_id'));

        $document->document_number = $request->get('document_number');
        $document->type()->associate($type);
        $document->process()->associate($process);

        $document->save();

        $revision->description = $request->get('description');
        $revision->revision_date = $request->get('revision_date');
        $revision->revision = $request->get('revision');
        $revision->change_description = $request->get('change_description');

        $revision->user()->associate(Auth::user());
        
        $revision->save();
        
        return redirect('revisions')->with('status', 'success')->with('message', 'Revision "'.$revision->revision.'" was updated successfully to document "'.$document->document_number.'".');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Revision  $revision
     * @return \Illuminate\Http\Response
     */
    public function destroy(Revision $revision)
    {
        //
    }
}
