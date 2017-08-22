<?php

namespace App\Http\Controllers;

use App\Revision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function revisionData()
    {
        //
        $revisions = new Revision();
        $revisions = $revisions->latestRevision()->with('document.part.customer','document.process','document.type')->get();
        foreach($revisions as $revision){
            $revision['revision'] = '<a href="'.action('DocumentController@show', $revision['document']['id']).'">'.$revision['revision'].'</a>'; 
            if (Auth::check()) {
            // The user is logged in...       
                $id = $revision['document']['id'];
                $revision['edit'] = '<a href="'.action('DocumentController@edit', $id).'"><button type="button" class="btn btn-outline-primary">edit</button></a>';
            }
        }
        return ['data'=>$revisions];
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
    }
}
