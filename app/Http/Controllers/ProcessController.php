<?php

namespace App\Http\Controllers;

use App\Process;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;
use App\Traits\dataTables;

class ProcessController extends Controller
{
    use dataTables;

    function __construct() 
    {
        $this->setControllerName('Process');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tableColumns = ['Process', 'Date Created'];
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
        $this->controllerName = 'Process';
        $processes = Process::get();
        return $this->dataTablesData($processes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $title = ['title' => 'New Process'];
        return view('forms.process', $title);
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
        $process = new Process([
            'name' => $request->get('name')
        ]);
        $process->save();
        return redirect('process')->with('status', 'success')->with('message', 'Process "'.$process->name.'" was added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function show(Process $process)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function edit(Process $process)
    {
        //
        $title = ['title' => 'Edit Process'];
        return view('forms.processEdit', $process, $title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Process $process)
    {
        //
        $oldName = $process->name;
        $newName = $request->get('name');
        $process->name =  $request->get('name');
        $process->save();
        return redirect('process')->with('status', 'success')->with('message', 'Process "'.$oldName.'" is now "'.$newName.'".');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function destroy(Process $process)
    {
        //
        try{
            $process->delete();
        }
        catch(QueryException $ex){
            //23000 Foriegn Key Exception aka already linked to another table
            if($ex->getcode() === '23000'){
                return redirect('process')->with('status', 'danger')->with('message', 'Process "'.$process->name.'" is linked, cannot be deleted.');
            }

        }
        
        return redirect('process')->with('status', 'success')->with('message', 'Process "'.$process->name.'" was deleted successfully.');
    }
}
