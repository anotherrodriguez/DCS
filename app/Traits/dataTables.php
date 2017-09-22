<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait dataTables
{
	public function setControllerName ($controllerName) 
	{
        $this->controllerName = $controllerName;
    }

    protected function dataTablesIndex($tableColumns, $dataColumns)
    {
        $columns = $this->addColumns($tableColumns, $dataColumns);
        $columns['url'] = action($this->controllerName.'Controller@tableData');
        $columns['title'] = $this->controllerName;
        return view('dataTable', $columns);
    }

    protected function addColumns($tableColumns, $dataColumns)
    {
        if (Auth::check()) {
            // The user is logged in...
            $tableColumns[] = ''; 
            $tableColumns[] = ''; 
            $dataColumns[] = 'edit';
            $dataColumns[] = 'delete';   
            }
            $columns = ['tableColumns' => $tableColumns, 'dataColumns' => $dataColumns];
            $columns['createUrl'] = action($this->controllerName.'Controller@create');
            if($this->controllerName === 'Document'){
            $columns['createUrl'] = action('PartController@selectPart');
            }
            return $columns;
    }

    protected function dataTablesData($dataTables)
    {
        if (Auth::check()) 
        {
            // The user is logged in...       
            foreach($dataTables as $dataTable)
            {
		        $id = $dataTable['id'];
		        $dataTable['edit'] = '<a href="'.action($this->controllerName.'Controller@edit', $id).'"><button type="button" class="btn btn-outline-warning">edit</button></a>';
		        $dataTable['delete'] = '<form class="deleteForm" action="'.action($this->controllerName.'Controller@destroy', $id).'" method="post">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-outline-danger deleteBtn">delete</button></form>';
            }
        }

        return ['data'=>$dataTables];
    }

    protected function listParts()
    {
        $tableColumns = ['Part Number', 'Customer', ''];
        $dataColumns = ['part_number', 'customer.name', 'select'];
        $columns = ['tableColumns' => $tableColumns, 'dataColumns' => $dataColumns];
        $columns = ['tableColumns' => $tableColumns, 'dataColumns' => $dataColumns];
        $columns['createUrl'] = action('PartController@create');
        $columns['url'] = action('PartController@partTableData');
        return view('dataTable', $columns);
    }
     protected function saveFiles($files,$fileTypes,$revision)
     {
         foreach($files as $key=>$file){
             $path = $file->store('public/documents');
             $path = substr($path, 7); //stupid workaround
             $fileType = \App\File::find($fileTypes[$key]);
             $file_revision = new \App\file_revision(['path'=>$path]);
             $file_revision->file()->associate($fileType);
             $file_revision->revision()->associate($revision);
 
             $file_revision->save();
             
         }
     }
}