@extends ('layouts.master')

@section ('content')
<div class='col-md-3'></div>
 	<div class='col-md-6'>
	 <form method="post" action="{{action('CollectionController@update', $collection['id'])}}">
 	    <div class="form-group row">
	    {{csrf_field()}}
	      <input name="_method" type="hidden" value="PATCH">
	      <label for="type" class="col-sm-3 col-form-label">Process</label>
		    <div class="col-sm-9">
		      <select name="process_id" class="form-control">
		        <option value="">Select Process...</option>
		      @foreach($processes as $process)
		      	@if($process['id'] === $collection['process']['id'])
		        <option value={{$process->id}} selected>{{$process->name}}</option>
		        @else
		        <option value={{$process->id}}>{{$process->name}}</option>
		        @endif
		      @endforeach
		      </select>
		     </div> 
        </div>    
	    <div class="form-group row">
	      <div class="offset-sm-3 col-sm-9">
	        <button type="submit" class="btn btn-primary">Update</button>
	      </div>
	    </div>
	  </form>
</div>
@endsection