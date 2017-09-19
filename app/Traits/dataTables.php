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

    protected function dataTablesData($dataTables, $viewColumns=false)
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

		if($this->controllerName === 'Document')
		{
			foreach($dataTables as $dataTable)
			{
				$dataTable['operation'] = '<a href="'.action('DocumentController@show', $dataTable['id']).'">'.str_pad($dataTable['operation'], 3, '0', STR_PAD_LEFT).'</a>';
                $dataTable['revision'] = '<a href="'.action('RevisionController@show', $dataTable['revision_id']).'">'.$dataTable['revision'].'</a>';
			}
		}

        if($viewColumns)
        {
            foreach($dataTables as $dataTable)
            {
                $dataTable['view'] = '<a target="_blank" href="'.action('RevisionController@showFile', $dataTable['id']).'"><button type="button" class="btn btn-outline-primary">view</button></a>';
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

    protected function saveFiles($files,$types,$revision)
    {
        foreach($files as $key=>$file){
            $filePath = $file->store('documents');
            $type = \App\Type::find($types[$key]);

            $revisions_files_types = new \App\revisions_files_types(['file_path'=>$filePath]);
            $revisions_files_types->type()->associate($type);
            $revisions_files_types->revision()->associate($revision);

            $revisions_files_types->save();
        }
    }



}