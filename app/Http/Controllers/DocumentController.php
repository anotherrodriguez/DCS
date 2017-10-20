<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use App\Traits\dataTables;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Database\QueryException;

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
       $tableColumns = ['Document Number', 'Revision', 'Type', 'Description', 'Customer'];
       $dataColumns = ['document_number', 'revision', 'type.name', 'description', 'part.customer.name'];
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
        $types = \App\Type::orderBy('name')->get();
        $files = \App\File::orderBy('name')->get();
        $processes = \App\Process::orderBy('name')->get();
        $data = ['part'=>$part, 'types'=>$types, 'files'=>$files,'processes'=>$processes, 'title'=>'Part'];
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
        $files = $request->file('file');
        $fileTypes = $request->get('file_id');
        $part = \App\Part::find($request->get('part_id'));
        $type = \App\Type::find($request->get('type_id'));
        $process = \App\Process::find($request->get('process_id'));

        $document = new Document([
            'document_number' =>  $request->get('document_number')
            ]);

        $document->part()->associate($part);
        $document->type()->associate($type);
        $document->process()->associate($process);

        try{
            $document->save();
        }

        catch(QueryException $ex){
            //23000 Foriegn Key Exception aka already linked to another table
            if($ex->getcode() == '23000'){
                return redirect('documents')->with('status', 'danger')->with('message', 'Error: Document "'.$document->document_number.'" of type "'.$type->name.'" already exists.');
            }
        }
        
        $revision = new \App\Revision([
            'description' => $request->get('description'),
            'revision_date' => $request->get('revision_date'),
            'revision' => $request->get('revision'),
            'change_description' => $request->get('change_description')
        ]);
        
        $revision->document()->associate($document);

        $revision->user()->associate(Auth::user());
        
        $revision->save();

        $this->saveFiles($files,$fileTypes,$revision);
        
        return redirect('documents')->with('status', 'success')->with('message', 'Revision "'.$revision->revision.'" was added successfully to document "'.$document->document_number.'".');
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
        $revision['summary']['document_number'] = $revisions['document_number'];
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
        
        foreach($documents as $document)
        {
            $document['document_number'] = '<a target="_blank" href="'.action('RevisionController@showFile', $document['revision_id']).'">'.$document['document_number'].'</a>';
            $document['revision'] = '<a href="'.action('RevisionController@show', $document['id']).'">'.$document['revision'].'</a>';            
        }

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
        $types = \App\Type::orderBy('name')->get();
        $processes = \App\Process::orderBy('name')->get();
        $data = ['document'=>$document, 'types'=>$types, 'processes'=>$processes, 'title'=>'document'];

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

        $document->document_number = $request->get('document_number');
        $document->type()->associate($type);
        $document->process()->associate($process);

        try{
            $document->save();
        }

        catch(QueryException $ex){
            //23000 Foriegn Key Exception aka already linked to another table
            if($ex->getcode() == '23000'){
                return redirect('documents')->with('status', 'danger')->with('message', 'Error: Document "'.$document->document_number.'" of type "'.$type->name.'" already exists.');
            }
        }
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
        try{
            $document->delete();
        }

        catch(QueryException $ex){
            //23000 Foriegn Key Exception aka already linked to another table
            if($ex->getcode() == '23000'){
                return redirect('documents')->with('status', 'danger')->with('message', 'Error: Document "'.$document->document_number.'" has multiple revisions.');
            }
        }
        return redirect('documents')->with('status', 'success')->with('message', 'Document "'.$document->document_number.'" was deleted successfully.');

    }
}
