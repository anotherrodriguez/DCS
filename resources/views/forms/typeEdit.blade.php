@extends ('layouts.master')

@section ('content')
<div class='col-lg-4'>
	 <form method="post" action="{{action('TypeController@update', $id)}}">
	    <div class="form-group row">
	    {{csrf_field()}}
	     <input name="_method" type="hidden" value="PATCH">
	      <label for="typeName" class="col-sm-2 col-form-label">Type</label>
	      <div class="col-sm-10">
	        <input type="text" name="name" class="form-control" id="typeName" value="{{$name}}">
	      </div>
	    </div>
	    <div class="form-group row">
	      <div class="offset-sm-2 col-sm-10">
	        <button type="submit" class="btn btn-primary">Update</button>
	      </div>
	    </div>
	  </form>
</div>
@endsection