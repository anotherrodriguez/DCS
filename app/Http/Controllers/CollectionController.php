<?php

namespace App\Http\Controllers;

use App\Collection;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use App\Traits\dataTables;
use Illuminate\Support\Facades\DB;


class CollectionController extends Controller
{
    use dataTables;

    function __construct() 
    {
        $this->setControllerName('Collection');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tableColumns = ['DocPack', 'Process', 'Description', 'Date Created', 'User'];
        $dataColumns = ['collection_id', 'process.name', 'description', 'created_at', 'user.name'];
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
        $collections = Collection::with('process','user')->get();
        foreach($collections as $collection)
        {
            $collection['collection_id'] = '<a href="'.action('CollectionController@show', $collection['id']).'">'.$this->padCollection($collection['id']).'</a>';
        }

      $jsonFile = fopen($this->controllerName.".json", "w") or die("Unable to open file!");
      fwrite($jsonFile, json_encode($this->dataTablesData($collections)));
      fclose($jsonFile);


        return $this->dataTablesData($collections);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $processes = \App\Process::orderBy('name','asc')->get();
        $data = ['processes'=>$processes, 'title'=>'Collection'];
        return view('forms.collection', $data);
    }

    public function createFromE9()
    {
        //
        $data = ['title'=>'Collection'];
        return view('forms.collectionE9', $data);
    }

        public function saveFromE9()
    {
        //
        $collections = Collection::with('process','user')->get();
        foreach($collections as $collection)
        {
            $collection['collection_id'] = '<a href="'.action('CollectionController@show', $collection['id']).'">'.$this->padCollection($collection['id']).'</a>';
        }

      $jsonFile = fopen($this->controllerName.".json", "w") or die("Unable to open file!");
      fwrite($jsonFile, json_encode($this->dataTablesData($collections)));
      fclose($jsonFile);


        return redirect('collections')->with('status', 'success')->with('message', 'Docpacks loaded from E9 successfully.');
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addDocumentsView(Collection $collection)
    {
        //
       $tableColumns = ['Document Number', 'Revision', 'Type', ''];
       $dataColumns = ['document_number', 'revision', 'type.name', 'addBtn'];
       $columns = ['tableColumns' => $tableColumns, 'dataColumns' => $dataColumns];
       $columns['url'] = action('CollectionController@showTableData', $collection);
       $columns['urlAll'] = action('CollectionController@showAllTableData', $collection);
       $columns['collectionId'] = $collection->id;
       $columns['title'] = 'Collection';
       $columns['collectionIdDisplay'] = $this->padCollection($collection->id).', '.$collection->description;
        return view('manageCollection', $columns);
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addDocument(Request $request)
    {
        //
        $collection = new \App\collection_document();
        $collection->document()->associate($request->get('documentId'));
        $collection->collection()->associate($request->get('collectionId'));
        $collection->save();

        $collection_log = new \App\Collection_Log(['document_change' => 1]);
        $collection_log->document()->associate($request->get('documentId'));
        $collection_log->collection()->associate($request->get('collectionId'));
        $collection_log->user()->associate(Auth::user());
        $collection_log->save();
    }

    public function removeDocument(Request $request)
    {
        //
       $collection_document = \App\collection_document::find($request->get('id'));


       $collection_log = new \App\Collection_Log(['document_change' => -1]);
       $collection_log->document()->associate($collection_document->document_id);
       $collection_log->collection()->associate($collection_document->collection_id);
       $collection_log->user()->associate(Auth::user());
       $collection_log->save();

       $collection_document->delete();
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
        $action = $request->get('action');
        $process = \App\Process::find($request->get('process_id'));
        $collection = new Collection(['description' => $request->get('description')]);
        $collection->process()->associate($process);
        $collection->user()->associate(Auth::user());
        $collection->save();

        $this->tableData();

        if($action === 'addOne')
        {
        return redirect('collections')->with('status', 'success')->with('message', 'New Collection "'.$this->padCollection($collection->id).'" was added successfully.');
        }
        else
        {
        return redirect('collections/create')->with('status', 'success')->with('message', 'New Collection "'.$this->padCollection($collection->id).'" was added successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function show(Collection $collection)
    {
        //
       $tableColumns = ['Document Number', 'Description', 'Revision', 'Type'];
       $dataColumns = ['document_number', 'description', 'revision', 'type.name'];
       $columns = ['tableColumns' => $tableColumns, 'dataColumns' => $dataColumns];
       $columns['url'] = action('CollectionController@showTableData', $collection);
       $columns['createUrl'] = action('CollectionController@addDocumentsView', $collection);
       $columns['title'] = 'Collection';
       return view('collectionDataTable', $columns);
    }

        public function showOperator(Request $request)
    {
        //
       $collection = Collection::find($request->get('collection'));

       if(empty($collection))
       {
        return redirect('/view')->with('status', 'danger')->with('message', 'DocPack does not exist.');
       }

       $tableColumns = [ 'Type', 'Revision'];
       $dataColumns = ['typeBtn', 'revision'];
       $columns = ['tableColumns' => $tableColumns, 'dataColumns' => $dataColumns];
       $columns['url'] = action('CollectionController@showTableData', $collection);
       $columns['collectionId'] = $collection->id;
       $columns['collectionIdDisplay'] = $this->padCollection($request->get('collection'));
       $columns['change_request'] = \App\change_request::all();
       return view('operatorResults', $columns);
    }

    public function showTableData(Collection $collection)
    {
        //
       $collection = $collection->with('collection_document')->find($collection->id);
       $latestRevision = array();
       foreach($collection->collection_document as $document)
       {
        $documents = new \App\Document();
        $temp = $documents->latestRevision()->with('type')->find($document->document_id);
        $temp['addBtn'] = '<button type="button" data-id="'.$document->id.'" class="btn btn-outline-danger removeBtn">remove</button>';
        $temp['typeBtn'] = '<a target="_blank" href="'.action('RevisionController@showFile', $temp->revision_id).'"><button type="button" class="btn btn-outline-primary">'.$temp->type->name.' '.$temp->document_number.'</button></a>';
        $temp['document_number'] = '<a target="_blank" href="'.action('RevisionController@showFile', $temp->revision_id).'">'.$temp['document_number'].'</a>';
        $latestRevision[] = $temp;
       }
        return ['data'=>$latestRevision];
        
    }

    public function showAllTableData(Collection $collection)
    {
        //
       $collection = $collection->with('collection_document')->find($collection->id);
       $document_ids = array();
       foreach($collection->collection_document as $document)
       {
        $document_ids[] = $document->document_id;
       }


       $documents = new \App\Document();
       $documents = $documents->latestRevision()->with('type')->whereNotIn('documents.id',$document_ids)->get();
        
        foreach($documents as $document)
        {
            $document['addBtn'] = '<button type="button" data-documentId="'.$document->id.'" class="btn btn-outline-primary addBtn">add</button>';
        }


        return ['data'=>$documents];
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function edit(Collection $collection)
    {
        //
        $collection = $collection::with('process')->find($collection->id);
        $processes = \App\Process::all();
        $data = ['collection'=>$collection, 'processes'=>$processes, 'title'=>'Collection'];

        return view('forms.collectionEdit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collection $collection)
    {
        //
        $process = \App\Process::find($request->get('process_id'));
        $collection = $collection->find($collection->id);
        $collection->description = $request->get('description');
        $collection->process()->associate($process);
        $collection->user()->associate(Auth::user());
        $collection->save();

        $this->tableData();
        

        return redirect('collections')->with('status', 'success')->with('message', 'Collection was updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collection $collection)
    {
        //
        $collection->delete();

        $this->tableData();
        
        return redirect('collections')->with('status', 'success')->with('message', 'Collection "'.$collection->id.'" was deleted successfully.');
    }

    public function showLog()
    {
        //
       $tableColumns = ['Document Number', 'Revision'];
       $dataColumns = ['document_number', 'revision'];
        $columns = ['tableColumns' => $tableColumns, 'dataColumns' => $dataColumns];
        $columns['url'] = action($this->controllerName.'Controller@log');
        $columns['title'] = 'Collection';
        return view('collectionLog', $columns);
        
    }

    public function log(Request $request)
    {
        //
        //$collections = \App\Collection_Log::select('document_id', DB::raw('SUM(document_change) as document_change'))->groupBy('document_id', 'collection_id')->where([['collection_id','=',2],['created_at', '<', '2017-11-11']])->with('document')->get();
        
        $collection_id = $request->get('docPack');
        $inputDate = $request->get('dateTime');

        $collections = DB::select( DB::raw("SELECT documents.document_number, revisions.revision, SUM(document_change) as document_state FROM collection_log JOIN documents ON documents.id=collection_log.document_id JOIN (SELECT `document_id`, MAX(`revision_date`) as `rev_date` FROM `revisions` WHERE created_at<:inputDate GROUP BY `document_id`) AS `max_rev` ON max_rev.document_id=collection_log.document_id JOIN revisions ON revisions.document_id=collection_log.document_id AND revisions.revision_date=max_rev.rev_date WHERE collection_id = :collection_id AND collection_log.created_at<:inputDate1 GROUP BY collection_log.document_id, documents.document_number, revisions.revision HAVING SUM(document_change)>0"), array('collection_id' => $collection_id, 'inputDate' => $inputDate, 'inputDate1' => $inputDate));

  
        return ['data'=>$collections];
        
    }
}
