@extends ('layouts.master')

@section ('content')
<div class='col-md-3'></div>
 	<div class='col-md-6'>
	 <form method="post" action="{{action('PartController@store')}}">
	    <div class="form-group row">
	    {{csrf_field()}}
	      <label for="part_number" class="col-sm-3 col-form-label">Part</label>
	      <div class="col-sm-9">
	        <input type="text" name="part_number" class="form-control" id="part_number" placeholder="Part Number">
	      </div>
	    </div>
	    <div class="form-group row">
	    	      <label for="customer" class="col-sm-3 col-form-label">Customer</label>
            <div class="col-sm-9">
             <select name="customer_id" class="form-control">
              @foreach($customers as $customer)
                <option value={{$customer['id']}}>{{$customer['name']}}</option>
              @endforeach
              </select>
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