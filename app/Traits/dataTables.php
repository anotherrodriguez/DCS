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
        if (Auth::check()) {
            // The user is logged in...
            $tableColumns[] = 'Edit'; 
            $tableColumns[] = 'Delete'; 
            $dataColumns[] = 'edit';
            $dataColumns[] = 'delete';   
            }
        $createUrl = action($this->controllerName.'Controller@create');
        $url = action($this->controllerName.'Controller@tableData', 3);
        $title = $this->controllerName;
        $columns = ['tableColumns' => $tableColumns, 'dataColumns' => $dataColumns, 'url' => $url, 'title' => $title, 'createUrl' => $createUrl];
        return view('dataTable', $columns);
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
		        $dataTable['delete'] = '<form action="'.action($this->controllerName.'Controller@destroy', $id).'" method="post">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-outline-danger">delete</button></form>';
            }
        }

		if($this->controllerName === 'Revision')
		{
			foreach($dataTables as $dataTable)
			{
				$dataTable['revision'] = '<a href="'.action('DocumentController@show', $dataTable['document']['id']).'">'.$dataTable['revision'].'</a>';
			}
		}

        return ['data'=>$dataTables];
    }
}