@extends ('layouts.master')

@section ('content')
<div class='col-md-3'></div>
 	<div class='col-md-6'>
	 <form method="post" action="{{action('CustomerController@store')}}">
	    <div class="form-group row">
	    {{csrf_field()}}
	      <label for="customerName" class="col-sm-3 col-form-label">Customer</label>
	      <div class="col-sm-9">
	        <input type="text" name="name" class="form-control" id="customerName" placeholder="Customer Name">
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