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
       $tableColumns = ['Part', 'Operation', 'Revision', 'Description', 'Customer'];
       $dataColumns = ['document.part.part_number', 'document.operation', 'revision', 'description', 'document.part.customer.name'];
       return $this->dataTablesIndex($tableColumns, $dataColumns);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
/*    public function tableData()
    {
        //
        $revisions = new Revision();
        $revisions = $revisions->latestRevision()->with('document.part.customer','document.process','document.type')->get();
        return $this->dataTablesData($revisions);

    }
*/
    public function tableData(\App\Document $document)
    {
        //
        $revisions = \App\Document::with('revision','type','part.customer', 'process')->find($document->id);
        
        $revision = $this->dataTablesData($revisions['revision']);
        $revision['summary']['operation'] = $revisions['operation'];
        $revision['summary']['customer'] = $revisions['part']['customer']['name'];
        $revision['summary']['type'] = $revisions['type']['name'];
        $revision['summary']['process'] = $revisions['process']['name'];
        return ['data'=>$revision['data'], 'summary'=>$revision['summary']];
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($document)
    {
        //
        $document = \App\Document::with('type','part.customer', 'process')->find($document);
        $types = \App\Type::all();
        $processes = \App\Process::all();
        $data = ['document'=>$document, 'types'=>$types, 'processes'=>$processes];
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
        $document = \App\Document::find($request->get('document_id'));

        $revision = new Revision([
            'description' => $request->get('description'),
            'revision_date' => $request->get('revision_date'),
            'revision' => $request->get('revision'),
            'change_description' => $request->get('change_description')
        ]);
        
        $revision->document()->associate($document);

        $revision->user()->associate(Auth::user());
        
        $revision->save();
        
        return redirect('documents')->with('status', 'success')->with('message', 'Revision "'.$revision->revision.'" was added successfully to document "'.$document->operation.'".');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Revision  $revision
     * @return \Illuminate\Http\Response
     */
    public function show($document)
    {
        //
        $document = \App\Document::find($document);
        $tableColumns = ['Revision', 'Description', 'Change', 'Date'];
        $dataColumns = ['revision', 'description', 'change_description', 'revision_date'];
        $columns = $this->addColumns($tableColumns, $dataColumns);
        $columns['url'] = action('RevisionController@tableData', $document);
        $columns['createUrl'] = url('revisions/create', $document);
        return view('document', $columns);
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
        $revision = $revision::with('document','document.part.customer','document.type','document.process')->find($revision->id);
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
        $part_number = $request->get('part_number'); 
        $revision->description = $request->get('description');
        $revision->revision_date = $request->get('revision_date');
        $revision->revision = $request->get('revision');
        $revision->change_description = $request->get('change_description');

        $revision->user()->associate(Auth::user());
        
        $revision->save();
        return redirect('documents')->with('status', 'success')->with('message', 'Revision "'.$revision->revision.'" was updated successfully for '.$part_number);
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
        $revision->delete();
        return redirect('documents')->with('status', 'success')->with('message', 'Revision "'.$revision->revision.'" was deleted successfully.');
    }
}
