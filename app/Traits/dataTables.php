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
            if($this->controllerName === 'Revision'){
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
}