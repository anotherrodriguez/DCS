<?php

namespace App\Http\Controllers;

use App\Part;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;
use App\Traits\dataTables;

class PartController extends Controller
{
    use dataTables;

    function __construct() 
    {
        $this->setControllerName('Part');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tableColumns = ['Part Number', 'Customer'];
        $dataColumns = ['part_number', 'customer.name'];
        return $this->dataTablesIndex($tableColumns, $dataColumns);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectPart()
    {
        //
        return $this->listParts();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function partLinked($part)
    {
        //
        $part = Part::with('customer')->where('id',$part)->first();
        return $part;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function tableData()
    {
        //
        $parts = Part::with('customer')->get();
        return $this->dataTablesData($parts);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function partTableData()
    {
        //
        $parts = Part::with('customer')->get();
        foreach($parts as $part)
            {
                $id = $part['id'];
                $part['select'] = '<a href="'.url('revisions/create', $id).'"><button type="button" class="btn btn-outline-primary">select</button></a>';
            }
        return ['data'=>$parts];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $customers['customers'] = CustomerController::listCustomers();
        return view('forms.part', $customers);
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
        $customer = \App\Customer::find($request->get('customer_id'));

        $part = new Part([
            'part_number' => $request->get('part_number'),
        ]);
        $part->customer()->associate($customer);
        $part->save();
        return redirect('parts')->with('status', 'success')->with('message', 'Type "'.$part->part_number.'" was added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function show(Part $part)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function edit(Part $part)
    {
        //
        $part = $part->with('customer')->find($part['id']);
        $part['customers'] = CustomerController::listCustomers();
        return view('forms.partEdit', $part);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Part $part)
    {
        //
        $part->part_number =  $request->get('part_number');
        $part->customer_id =  $request->get('customer_id');
        $part->save();
        return redirect('parts')->with('status', 'success')->with('message', 'Part "'.$part->part_number.'" updated successfully".');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function destroy(Part $part)
    {
        //
        try
        {
           $part->delete(); 
        }
        catch(QueryException $ex){
            //23000 Foriegn Key Exception aka already linked to another table
            if($ex->getcode() === '23000'){
                return redirect('parts')->with('status', 'danger')->with('message', 'Type "'.$part->part_number.'" is linked, cannot be deleted.');
            }

        }

        return redirect('parts')->with('status', 'success')->with('message', 'Part "'.$part->part_number.'" was deleted successfully.');

    }
}
