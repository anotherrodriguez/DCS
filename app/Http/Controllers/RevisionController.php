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
       $dataColumns = ['document.part.part_number', 'document.operation', 'revision', 'description', 'document.part.customer.name'];
       return $this->dataTablesIndex($tableColumns, $dataColumns);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tableData(Revision $revision)
    {
        //
        $revisions = $revision::with('document.part.customer','document.process','revisions_files_types.type')->find($revision->id);
        

        $revision = $this->dataTablesData($revisions['revisions_files_types'],true);
        $revision['summary']['operation'] = $revisions['document']['operation'];
        $revision['summary']['customer'] = $revisions['document']['part']['customer']['name'];
        $revision['summary']['process'] = $revisions['document']['process']['name'];
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
        $document = \App\Document::with('part.customer', 'process')->find($document);
        $types = \App\Type::all();
        $processes = \App\Process::all();
        $data = ['document'=>$document, 'processes'=>$processes, 'types'=>$types];
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
        $types = $request->get('type_id');

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

        $this->saveFiles($files,$types,$revision);
        
        return redirect('documents')->with('status', 'success')->with('message', 'Revision "'.$revision->revision.'" was added successfully to document "'.$document->operation.'".');
    
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
        //$document = \App\Document::find($document);
        $tableColumns = ['Type', 'Date', ''];
        $dataColumns = ['type.name', 'created_at', 'view'];
        $columns = $this->addColumns($tableColumns, $dataColumns);
        $columns['url'] = action('RevisionController@tableData', $revision);
        $columns['createUrl'] = url('revisions/create', $revision);
        return view('revision', $columns);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Revision  $revision
     * @return \Illuminate\Http\Response
     */
    public function showFile($file)
    {
        //
        $file = \App\revisions_files_types::find($file);
        $path = 'storage/'.$file['file_path'];
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="test"'
]);
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
        $revision = $revision::with('document','document.part.customer','document.process')->find($revision->id);
        $processes = \App\Process::all();
        $data = ['revision'=>$revision, 'processes'=>$processes];

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
