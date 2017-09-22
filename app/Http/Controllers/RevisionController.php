<?php

namespace App\Http\Controllers;

use App\Revision;
use Illuminate\Http\Request;
use App\Traits\dataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Response;

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
       $dataColumns = ['document.part.part_number', 'document.document_number', 'revision', 'description', 'document.part.customer.name'];
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
        $revisions = \App\Document::with('revision','type','part.customer', 'process', 'revision.file_revision.file')->find($document->id);
        foreach($revisions['revision'] as $revision)
        {    
            foreach($revision['file_revision'] as $file)
            {
                $revision['download'] .= '<a href="'.action('RevisionController@downloadFile', $file['id']).'"><button type="button" class="btn btn-outline-primary">'.$file['file']['name'].'</button></a>';
            }
           
        }

        $revision = $this->dataTablesData($revisions['revision']);
        $revision['summary']['document_number'] = $revisions['document_number'];
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
        $files = \App\File::all();
        $processes = \App\Process::all();
        $data = ['document'=>$document, 'types'=>$types, 'files'=>$files, 'processes'=>$processes];
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
        $files = $request->file('file');
        $fileTypes = $request->get('file_id');
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

        $this->saveFiles($files,$fileTypes,$revision);
        
        return redirect('documents')->with('status', 'success')->with('message', 'Revision "'.$revision->revision.'" was added successfully to document "'.$document->document_number.'".');
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
        $tableColumns = ['Revision', 'Description', 'Change', 'Revision Date', 'Upload Date', 'Download'];
        $dataColumns = ['revision', 'description', 'change_description', 'revision_date', 'created_at', 'download'];
        $columns = $this->addColumns($tableColumns, $dataColumns);
        $columns['url'] = action('RevisionController@tableData', $document);
        $columns['createUrl'] = url('revisions/create', $document);
        return view('document', $columns);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Revision  $revision
     * @return \Illuminate\Http\Response
     */
    public function showFile(Revision $revision)
    {
        //

        $revision = $revision->with(['file_revision' => function($query){ $query->where('file_id',2); }])->find($revision->id);

        $path = 'storage/'.$revision->file_revision[0]->path;

        return response()->file($path);
            
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Revision  $revision
     * @return \Illuminate\Http\Response
     */
    public function downloadFile(\App\File_Revision $file_revision)
    {
        //

        $pathToFile = 'storage/'.$file_revision->path;

        return response()->download($pathToFile);
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
        $revision = $revision::with('document','document.part.customer','document.type','document.process', 'file_revision.file')->find($revision->id);
        $types = \App\Type::all();
        $files = \App\File::all();
        $processes = \App\Process::all();
        $data = ['revision'=>$revision, 'types'=>$types,'files'=>$files, 'processes'=>$processes];
        return $revision;
        //return view('forms.revisionEdit', $data);
        
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
