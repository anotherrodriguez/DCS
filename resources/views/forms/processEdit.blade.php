@extends ('layouts.master')

@section ('content')
<div class='col-lg-4'>
	 <form method="post" action="{{action('ProcessController@update', $id)}}">
	    <div class="form-group row">
	    {{csrf_field()}}
	     <input name="_method" type="hidden" value="PATCH">
	      <label for="customerName" class="col-sm-2 col-form-label">Process</label>
	      <div class="col-sm-10">
	        <input type="text" name="name" class="form-control" id="customerName" value="{{$name}}">
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