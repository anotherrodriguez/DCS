@extends ('layouts.master')

@section ('content')
<div class='col-md-3'></div>
 	<div class='col-md-6'>
	 <form method="post" action="{{action('TypeController@update', $id)}}">
	    <div class="form-group row">
	    {{csrf_field()}}
	     <input name="_method" type="hidden" value="PATCH">
	      <label for="typeName" class="col-sm-3 col-form-label">Type</label>
	      <div class="col-sm-9">
	        <input type="text" name="name" class="form-control" id="typeName" value="{{$name}}">
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