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
        $revisions = \App\Document::with('revision','type','part.customer', 'process', 'revision.file_revision.file', 'revision.user')->find($document->id);
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
        $document = \App\Document::with('type','part.customer', 'process', 'revision')->find($document);
        $types = \App\Type::orderBy('name')->get();
        $files = \App\File::orderBy('name')->get();
        $processes = \App\Process::orderBy('name')->get();
        $data = ['document'=>$document, 'types'=>$types, 'files'=>$files, 'processes'=>$processes, 'title'=>'Revision'];
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

        $DocumentController = new DocumentController();

        $DocumentController->tableData('Document');
        
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
        $tableColumns = ['Revision', 'Description', 'Change', 'Revision Date', 'Upload Date', 'User', 'Download'];
        $dataColumns = ['revision', 'description', 'change_description', 'revision_date', 'created_at', 'user.name', 'download'];
        $columns = $this->addColumns($tableColumns, $dataColumns);
        $columns['url'] = action('RevisionController@tableData', $document);
        $columns['createUrl'] = url('revisions/create', $document);
        $columns['title'] = 'Revision';
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

        $revision = $revision->with(['file_revision' => function($query){ $query->where('file_id',2); },'document'])->find($revision->id);

        $path = 'storage/'.$revision->file_revision[0]->path;

        

        $document_number = $revision->document->document_number;
        $document_number = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $document_number);
        $revision = $revision->revision;
        $name =  $document_number.', Rev '.$revision.'.pdf';

        $inline = 'inline; filename="'.$name.'"';

        $headers = ['Content-Disposition'=> $inline];
        
        return response()->file($path,$headers);
            
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

        
        $file = $file_revision::with('revision.document')->find($file_revision->id);
        $pathToFile = 'storage/'.$file_revision->path;
        $ext = '.'.explode('.',$pathToFile)[1];
        $document_number = $file->revision->document->document_number;
        $document_number = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $document_number);
        $revision = $file->revision->revision;
        $revision = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $revision);
        $name =  $document_number.', Rev '.$revision.$ext;
        return response()->download($pathToFile,$name);
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
        $types = \App\Type::orderBy('name')->get();
        $files = \App\File::orderBy('name')->get();
        $processes = \App\Process::orderBy('name')->get();
        $data = ['revision'=>$revision, 'types'=>$types,'files'=>$files, 'processes'=>$processes, 'title'=>'Revision'];
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

        $files = $request->file('file');        
        $fileTypes = $request->get('file_id');
        $filesToDelete = $request->get('filesToDelete');
        $part_number = $request->get('part_number'); 
        $revision->description = $request->get('description');
        $revision->revision_date = $request->get('revision_date');
        $revision->revision = $request->get('revision');
        $revision->change_description = $request->get('change_description');

        if(!empty($filesToDelete))
        {
            $this->deleteFileRevisions($filesToDelete);

        }

        $revision->user()->associate(Auth::user());
        
        $revision->save();
        if(!empty($files))
        {
             $this->saveFiles($files,$fileTypes,$revision);
         }

         $DocumentController = new DocumentController();

         $DocumentController->tableData('Document');

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
        $numberOfRevisions = $revision->where('document_id','=',$revision->document_id)->count();


            $file_revisions = \App\File_Revision::where('revision_id','=',$revision->id)->select('id')->get();
            foreach($file_revisions as $file_revision)
            {
                $filesToDelete[] = $file_revision->id;
            }
            $this->deleteFileRevisions($filesToDelete);

            $revision->delete();

            if($numberOfRevisions === 1){
                $document = \App\Document::find($revision->document_id);
                try{
                $document->delete();
                }

                catch(QueryException $ex){
                    //23000 Foriegn Key Exception aka already linked to another table
                    if($ex->getcode() == '23000'){
                        return redirect('documents')->with('status', 'danger')->with('message', 'Error: Document "'.$document->document_number.'" has multiple links.');
                    }
                }

            }

         $DocumentController = new DocumentController();

         $DocumentController->tableData('Document');

        return redirect('documents')->with('status', 'success')->with('message', 'Revision "'.$revision->revision.'" was deleted successfully.');
    }
}
