@extends ('layouts.master')

@section ('content')
<div class='col-md-3'></div>
 	<div class='col-md-6'>
	 <form method="post" action="{{action('ECNController@update', $id)}}">
	    <div class="form-group row">
	    {{csrf_field()}}
	     <input name="_method" type="hidden" value="PATCH">
	      <label for="customerName" class="col-sm-3 col-form-label">ECN</label>
	      <div class="col-sm-9">
	        <input type="text" name="id" class="form-control" value="{{$id}}" disabled>
	      </div>
	    </div>
	    <div class="form-group row">
	      <label for="Part Number" class="col-sm-3 col-form-label">Part Number</label>
	      <div class="col-sm-9">
	        <input type="text" name="part_number" class="form-control" value="{{$part_number}}" disabled>
	      </div>
	    </div>
	    <div class="form-group row">
	      <label for="Operator" class="col-sm-3 col-form-label">Operator</label>
	      <div class="col-sm-9">
	        <input type="text" name="operator" class="form-control" value="{{$operator}}" disabled>
	      </div>
	    </div>
	    <div class="form-group row">
	      <label for="seq" class="col-sm-3 col-form-label">Sequence Number</label>
	      <div class="col-sm-9">
	        <input type="text" name="seq" class="form-control" value="{{$sequence_number}}" disabled>
	      </div>
	    </div>
	    <div class="form-group row">
	      <label for="Change Request" class="col-sm-3 col-form-label">Change Request</label>
	      <div class="col-sm-9">
	        <input type="text" name="change_request" class="form-control" value="{{$change_request['name']}}" disabled>
	      </div>
	    </div>
	    <div class="form-group row">
	      <label for="Notes" class="col-sm-3 col-form-label">Notes</label>
	      <div class="col-sm-9">
            {{$notes}}
	      </div>
	    </div>
	    <div class="form-group row">
	    	      <label for="status" class="col-sm-3 col-form-label">Assigned Engineer</label>
            <div class="col-sm-9">
             <select name="user_id" class="form-control" required>
              @foreach($users as $user_list)
		      	@if($user_list['id'] === $user['id'])
		        <option value={{$user_list->id}} selected>{{$user_list->name}}</option>
		        @else
                <option value={{$user_list->id}}>{{$user_list->name}}</option>
                @endif
              @endforeach
              </select>
              </div> 
        </div>
	    <div class="form-group row">
	    	      <label for="status" class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-9">
             <select name="status_id" class="form-control" required>
              @foreach($statuses as $status_list)
		      	@if($status_list['id'] === $status['id'])
		        <option value={{$status_list->id}} selected>{{$status_list->name}}</option>
		        @else
                <option value={{$status_list->id}}>{{$status_list->name}}</option>
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