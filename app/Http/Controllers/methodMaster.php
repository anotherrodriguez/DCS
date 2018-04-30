<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class methodMaster extends Controller
{
    //
    public function show($partNum){
    	return $partNum;
    }
}
