@extends ('layouts.master')

@section ('content')
<div class='col-md-3'></div>
 	<div class='col-md-6'>
	 <form method="post" action="{{action('CollectionController@store')}}" enctype="multipart/form-data">
	    {{csrf_field()}}
 	    <div class="form-group row">
	      <label for="type" class="col-sm-3 col-form-label">Process</label>
		    <div class="col-sm-9">
		      <select name="process_id" class="form-control" required>
		        <option value="">Select Process...</option>
		      @foreach($processes as $process)
		        <option value={{$process->id}}>{{$process->name}}</option>
		      @endforeach
		      </select>
		     </div> 
        </div> 
                <div class="form-group row">
	    	      <label for="description" class="col-sm-3 col-form-label">Description</label>
            <div class="col-sm-9">
	            <textarea class="form-control" name="description" placeholder="Description" required></textarea>
             </div> 
        </div> 
	   
	    <div class="form-group row">
	      <div class="offset-sm-3 col-sm-9">
	        <button type="submit" class="btn btn-primary">Add</button>
	      </div>
	    </div>
	  </form>
</div>
@endsection
