@extends ('layouts.master')

@section ('content')
<div class='col-lg-4'>
	 <form method="post" action="{{action('PartController@update', $id)}}">
	    <div class="form-group row">
	    {{csrf_field()}}
	     <input name="_method" type="hidden" value="PATCH">
	      <label for="part_number" class="col-sm-2 col-form-label">Part</label>
	      <div class="col-sm-10">
	        <input type="text" name="part_number" class="form-control" id="part_number" value={{$part_number}}>
	      </div>
	    </div>
	    <div class="form-group row">
	    	      <label for="customer" class="col-sm-2 col-form-label">Customer</label>
            <div class="col-sm-10">
             <select name="customer_id" class="form-control">
              @foreach($customers as $customer)
	              @if($customer_id === $customer['id'])
                <option value={{$customer['id']}} selected>{{$customer['name']}}</option>
                  @else
                <option value={{$customer['id']}}>{{$customer['name']}}</option>
                  @endif
              @endforeach
              </select>
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