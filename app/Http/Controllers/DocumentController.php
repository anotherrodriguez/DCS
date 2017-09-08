<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use App\Traits\dataTables;
use Illuminate\Support\Facades\Auth;

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
       $tableColumns = ['Part', 'Operation', 'Revision', 'Type', 'Customer'];
       $dataColumns = ['part.part_number', 'operation', 'revision', 'type.name', 'part.customer.name'];
       return $this->dataTablesIndex($tableColumns, $dataColumns);
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
        return view('forms.document', $data);
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

        $document = new Document([
            'operation' =>  $request->get('operation')
            ]);

        $document->part()->associate($part);
        $document->type()->associate($type);
        $document->process()->associate($process);

        $document->save();

        
        $revision = new \App\Revision([
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
/*    public function tableData(Document $document)
    {
        //
        $revisions = $document->with('revision','type','part.customer', 'process')->find($document->id);
        
        $revision = $this->dataTablesData($revisions['revision']);
        $revision['summary']['operation'] = $revisions['operation'];
        $revision['summary']['customer'] = $revisions['part']['customer']['name'];
        $revision['summary']['type'] = $revisions['type']['name'];
        $revision['summary']['process'] = $revisions['process']['name'];
        return ['data'=>$revision['data'], 'summary'=>$revision['summary']];
        
    }
    */

    public function tableData()
    {
        //
        $documents = new Document();
        $documents = $documents->latestRevision()->with('part.customer','process','type')->get();
        return $this->dataTablesData($documents);
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
        $document = $document::with('part.customer','type','process')->find($document->id);
        $types = \App\Type::all();
        $processes = \App\Process::all();
        $data = ['document'=>$document, 'types'=>$types, 'processes'=>$processes];

        return view('forms.documentEdit', $data);
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
        $type = \App\Type::find($request->get('type_id'));
        $process = \App\Process::find($request->get('process_id'));
        $document = $document->find($document->id);

        $document->operation = $request->get('operation');
        $document->type()->associate($type);
        $document->process()->associate($process);

        $document->save();
        
        return redirect('documents')->with('status', 'success')->with('message', 'Document was updated successfully.');
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
        $document->delete();
        return redirect('documents')->with('status', 'success')->with('message', 'Document "'.$document->operation.'" was deleted successfully.');

    }
}
