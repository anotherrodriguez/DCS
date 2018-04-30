<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\dataTables;
use Illuminate\Support\Facades\Auth;

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
        use dataTables;
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

        $result[0]['EstSetHours'] = number_format($result[0]['EstSetHours'],2);
        $result[0]['ProdStandard'] = number_format($result[0]['ProdStandard'],2);
        return $result[0];
	
	}

    public function methodMaster($partNum)
    {
        //  
        $con = new connect();
        $query = "SELECT PartOpr.*,PartRev.DrawNum, PartRev.RevDescription FROM PUB.PartOpr JOIN PUB.PartRev ON PartRev.PartNum=PartOpr.PartNum AND PartRev.RevisionNum=PartOpr.RevisionNum WHERE PartOpr.PartNum='$partNum' AND PartRev.Approved='1'";
        $result = $con->query($query);
        $methodData = [];
        //return $result;



        foreach($result as $rows)
        {
            $rows['Number06'] =  $this->padCollection((int)$rows['Number06']);
            $rows['documents'] = [];
            $rows['CommentText'] = utf8_encode ($rows['CommentText']);
            if($rows['Number06']>0){
            $collection = \App\Collection::with('collection_document.document')->find($rows['Number06']);
                   $latestRevision = array();
                   foreach($collection->collection_document as $document)
                   {
                    $documents = new \App\Document();
                    $temp = $documents->latestRevision()->find($document->document_id);
                    $latestRevision[] = $temp;
                   }

            $rows['documents'] = $latestRevision;
            
        }
        $methodData[] = $rows;
        }



        $data['rows'] = $methodData;
        $data['title'] = 'Collection';
        //return $data;
        return view('methodMaster', $data);

    
    }

    public function docpacks(Request $request)
    {
        $con = new connect();
        $partNum = $request->get('partNum');
        $rev = $request->get('rev');
        $query = "SELECT OprSeq,OpCode,PartNum FROM PUB.PartOpr WHERE PartOpr.PartNum='65351-11568-102' AND PartOpr.RevisionNum='L' AND AltMethod='J";
        $query = "SELECT OprSeq,OpCode,PartNum FROM PUB.PartOpr WHERE PartOpr.PartNum='$partNum' AND PartOpr.RevisionNum='$rev' AND Company='PGI'";
        $result = $con->query($query);
        $methodData = [];

        if(!$result){
        return redirect('collections')->with('status', 'danger')->with('message', 'Part number not found.');
        }
        
     

        foreach($result as $rows)
        {
            $methodData[] = $rows;
            $description = 'Seq '.$rows['OprSeq'].' '.$rows['PartNum'].' '.$rows['OpCode'];
            $collection = new \App\Collection(['description' => $description]);
            $collection->process()->associate(37);
            $collection->user()->associate(Auth::user());
            $collection->save();


        }

        return redirect()->route('saveFromE9');

    }
}
