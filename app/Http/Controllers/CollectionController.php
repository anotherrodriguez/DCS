<?php

namespace App\Http\Controllers;

use App\Collection;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use App\Traits\dataTables;


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
        $tableColumns = ['Collection', 'Process', 'Description', 'Date Created', 'User'];
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
       $columns['collectionIdDisplay'] = $this->padCollection($collection->id);
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
    }

    public function removeDocument(Request $request)
    {
        //
       $collection_document = \App\collection_document::find($request->get('id'));
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
        $process = \App\Process::find($request->get('process_id'));
        $collection = new Collection(['description' => $request->get('description')]);
        $collection->process()->associate($process);
        $collection->user()->associate(Auth::user());
        $collection->save();

        return redirect('collections')->with('status', 'success')->with('message', 'New Collection "'.$this->padCollection($collection->id).'" was added successfully.');
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
       $tableColumns = ['Document Number', 'Revision', 'Type'];
       $dataColumns = ['document_number', 'revision', 'type.name'];
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
        return redirect('/view')->with('status', 'danger')->with('message', 'Tech Number does not exist.');
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
        $temp['typeBtn'] = '<a target="_blank" href="'.action('RevisionController@showFile', $temp->revision_id).'"><button type="button" class="btn btn-outline-primary">'.$temp->type->name.'</button></a>';
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
        return redirect('collections')->with('status', 'success')->with('message', 'Collection "'.$collection->id.'" was deleted successfully.');
    }
}
