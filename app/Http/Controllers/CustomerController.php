<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\dataTables;

class CustomerController extends Controller
{
    use dataTables;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index','show','customerData']]);
        $this->setControllerName('Customer');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tableColumns = ['Customer', 'Date Created'];
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
        $customers = Customer::get();
        return $this->dataTablesData($customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $title = ['title' => 'New Customer'];
        return view('forms.customer', $title);
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
        $customer = new Customer([
            'name' => $request->get('name')
        ]);
        $customer->save();
        return redirect('customers')->with('status', 'success')->with('message', 'Customer "'.$customer->name.'" was added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
        $title = ['title' => 'Edit Customer'];
        return view('forms.customerEdit', $customer, $title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
        $oldName = $customer->name;
        $newName = $request->get('name');
        $customer->name =  $request->get('name');
        $customer->save();
        return redirect('customers')->with('status', 'success')->with('message', 'Customer "'.$oldName.'" is now "'.$newName.'".');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
        try{
            $customer->delete();
        }
        catch(QueryException $ex){
            //23000 Foriegn Key Exception aka already linked to another table
            if($ex->getcode() === '23000'){
                return redirect('customers')->with('status', 'danger')->with('message', 'Customer "'.$customer->name.'" is linked, cannot be deleted.');
            }

        }
        
        return redirect('customers')->with('status', 'success')->with('message', 'Customer "'.$customer->name.'" was deleted successfully.');
    }
}
