<?php

namespace App\Http\Controllers;

use App\ECN;
use Illuminate\Http\Request;
use App\Traits\dataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ECNSubmitted;
use Illuminate\Mail\Markdown;

class ECNController extends Controller
{
    use dataTables;
    
        public function __construct()
    {
        $this->setControllerName('ECN');
    }    

    public function mail()
    {
        //
        
        $ecn = ECN::find(1);
        $ECNSubmitted = new ECNSubmitted($ecn);
        //Mail::to('arodriguez@precisiongearinc.com')->send($ECNSubmitted);
        $markdown = new Markdown(view(), config('mail.markdown'));
        return $markdown->render('ECNemail',['ecn'=>$ecn]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        /*
        $ecn = ECN::find(1);
        $ECNSubmitted = new ECNSubmitted($ecn);
        Mail::to('arodriguez@precisiongearinc.com')->send($ECNSubmitted);
        */

        $tableColumns = ['ECN', 'Doc Pack', 'Part Number', 'Operator', 'Seq', 'Change Request', 'Notes','Engineer', 'Status', 'Date Created'];
        $dataColumns = ['id', 'collection_id', 'part_number', 'operator', 'sequence_number', 'change_request.name', 'notes',  'user.name','status.name', 'created_at'];
        if (Auth::check()) 
            {
            // The user is logged in...
            $tableColumns[] = ''; 
            $dataColumns[] = 'edit';
            }


            $columns = ['tableColumns' => $tableColumns, 'dataColumns' => $dataColumns];
            $columns['url'] = action($this->controllerName.'Controller@tableData');
            $columns['title'] = $this->controllerName;
            $columns['createUrl'] = 0;
            
            return view('dataTable', $columns);
    }   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tableData()
    {
        //
        $ecn = ECN::with('change_request','status','user')->get();
        if (Auth::check()) 
            {
            foreach($ecn as $dataTable)
                {
                    if(Auth::getUser()->id === $dataTable->user_id || \App\User::find(Auth::getUser()->id)->hasRole('manager'))
                    {
                        $id = $dataTable['id'];
                        $dataTable['edit'] = '<a href="'.action($this->controllerName.'Controller@edit', $id).'"><button type="button" class="btn btn-outline-warning">edit</button></a>';
                    }
                    else
                    {
                        $dataTable['edit'] = "";
                    }
                }
            }
            

        return ['data'=>$ecn];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $change_request = $request->get('change_request');
        $notes = $request->get('notes');

        if($change_request === "1")
        {
            $current_setup = $request->get('current_setup');
            $current_production = $request->get('current_production');
            $proposed_setup = $request->get('setup');
            $proposed_production = $request->get('production');
            $notes = "Current Setup:".$current_setup." - Proposed Setup:".$proposed_setup."; Current Production:".$current_production." - Proposed Production:".$proposed_production."; ".$notes;
        }

        $ecn = new ECN([
            'operator' =>  $request->get('operator'),
            'part_number' =>  $request->get('part_number'),
            'sequence_number' =>  $request->get('sequence_number'),
            'notes' =>  $notes
            ]);

        $ecn->collection()->associate($request->get('collectionId'));
        $ecn->change_request()->associate($change_request);
        $ecn->status()->associate(1);
        $ecn->user()->associate(6);

        $ecn->save();

        $ECNSubmitted = new ECNSubmitted($ecn);
        Mail::to('arodriguez@precisiongearinc.com')->send($ECNSubmitted);

        return redirect('showOperator?collection='.$request->get('collectionId'))->with('status', 'success')->with('message', 'ECN was submitted successfully.');



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ECN  $eCN
     * @return \Illuminate\Http\Response
     */
    public function show(ECN $eCN)
    {
        //
    }
    
        /**
     * Display the specified resource.
     *
     * @param  \App\ECN  $eCN
     * @return \Illuminate\Http\Response
     */
    public function showCollection($collection)
    {
        //
        $ecn = ECN::with('change_request','status','user')->where('collection_id','=', $collection)->get();


        return ['data'=>$ecn];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ECN  $eCN
     * @return \Illuminate\Http\Response
     */
    public function edit(ECN $ecn)
    {
        //
        $ecn = $ecn->with('change_request','status','user')->find($ecn->id);
        $ecn['title'] = 'ECN';
        $ecn['statuses'] = \App\ecn_status::all();
        $ecn['users'] = \App\User::all();
        return view('forms.ecnEdit',$ecn);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ECN  $eCN
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ECN $ecn)
    {
        //
        $ecn->user()->associate($request->get('user_id'));
        $ecn->status()->associate($request->get('status_id'));
        
        $ecn->save();

        $ECNSubmitted = new ECNSubmitted($ecn);
        Mail::to('arodriguez@precisiongearinc.com')->send($ECNSubmitted);

        return redirect('ecns')->with('status', 'success')->with('message', 'ECN was updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ECN  $eCN
     * @return \Illuminate\Http\Response
     */
    public function destroy(ECN $eCN)
    {
        //
    }
}
