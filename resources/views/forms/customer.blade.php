@extends ('layouts.master')

@section ('content')
<div class='col-lg-4'>
	 <form method="post" action="{{action('CustomerController@store')}}">
	    <div class="form-group row">
	    {{csrf_field()}}
	      <label for="customerName" class="col-sm-2 col-form-label">Customer</label>
	      <div class="col-sm-10">
	        <input type="text" name="name" class="form-control" id="customerName" placeholder="Customer Name">
	      </div>
	    </div>
	    <div class="form-group row">
	      <div class="offset-sm-2 col-sm-10">
	        <button type="submit" class="btn btn-primary">Add</button>
	      </div>
	    </div>
	  </form>
</div>
@endsection