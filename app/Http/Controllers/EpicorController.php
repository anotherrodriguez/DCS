<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class connect{
	public function query($query)
	{
        $con = odbc_connect("Epicor905Live","amozsolits","AccountMoz1936!");
        $result = odbc_exec($con, $query);
        $records = array();

		 if (!$result) {
		    return 'error';
		  	} 

  		while($table_rows = odbc_fetch_array($result)){
			$records[] = $table_rows;
    	}
    	return $records;
	}
}

class EpicorController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function display(Request $request)
    {
        //	
        $con = new connect();
        $collectionId = $request->get('collectionId');
        $query = "SELECT * FROM PUB.PartOpr WHERE Number06=$collectionId";
        $result = $con->query($query);
        return $result[0];
	
	}
}
