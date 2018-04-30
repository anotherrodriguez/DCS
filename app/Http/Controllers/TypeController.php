<?php

namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;
use App\Traits\dataTables;

class TypeController extends Controller
{
    use dataTables;

    function __construct() 
    {
        $this->setControllerName('Type');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tableColumns = ['Type', 'Date Created'];
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
        $types = Type::get();

      $jsonFile = fopen($this->controllerName.".json", "w") or die("Unable to open file!");
      fwrite($jsonFile, json_encode($this->dataTablesData($types)));
      fclose($jsonFile);

        return $this->dataTablesData($types);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $title = ['title' => 'New Type'];
        return view('forms.type', $title);
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
        $type = new Type([
            'name' => $request->get('name')
        ]);
        $type->save();

        $this->tableData();

        return redirect('types')->with('status', 'success')->with('message', 'Type "'.$type->name.'" was added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        //
        $title = ['title' => 'Edit Type'];
        return view('forms.typeEdit', $type, $title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        //
        $oldName = $type->name;
        $newName = $request->get('name');
        $type->name =  $request->get('name');
        $type->save();

        $this->tableData();

        return redirect('types')->with('status', 'success')->with('message', 'Type "'.$oldName.'" is now "'.$newName.'".');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        //
        try{
            $type->delete();
        }
        catch(QueryException $ex){
            //23000 Foriegn Key Exception aka already linked to another table
            if($ex->getcode() === '23000'){
                return redirect('types')->with('status', 'danger')->with('message', 'Type "'.$type->name.'" is linked, cannot be deleted.');
            }

        }
        
        $this->tableData();
        
        return redirect('types')->with('status', 'success')->with('message', 'Type "'.$type->name.'" was deleted successfully.');
    }
}
