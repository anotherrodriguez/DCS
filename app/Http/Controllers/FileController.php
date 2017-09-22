<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;
use App\Traits\dataTables;

class FileController extends Controller
{
    use dataTables;

    function __construct() 
    {
        $this->setControllerName('File');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tableColumns = ['File', 'Date Created'];
        $dataColumns = ['name', 'created_at'];
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
        $files = File::get();
        return $this->dataTablesData($files);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $title = ['title' => 'New File'];
        return view('forms.file', $title);
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
        $file = new File([
            'name' => $request->get('name')
        ]);
        $file->save();
        return redirect('files')->with('status', 'success')->with('message', 'File "'.$file->name.'" was added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
        $title = ['title' => 'Edit File'];
        return view('forms.fileEdit', $file, $title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
        $oldName = $file->name;
        $newName = $request->get('name');
        $file->name =  $request->get('name');
        $file->save();
        return redirect('files')->with('status', 'success')->with('message', 'File "'.$oldName.'" is now "'.$newName.'".');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        //
        try{
            $file->delete();
        }
        catch(QueryException $ex){
            //23000 Foriegn Key Exception aka already linked to another table
            if($ex->getcode() === '23000'){
                return redirect('files')->with('status', 'danger')->with('message', 'File "'.$file->name.'" is linked, cannot be deleted.');
            }

        }
        
        return redirect('files')->with('status', 'success')->with('message', 'File "'.$file->name.'" was deleted successfully.');
    }
}
