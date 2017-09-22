@extends ('layouts.master')

@section ('content')
<div class='col-md-3'></div>
 	<div class='col-md-6'>
	 <form method="post" action="{{action('DocumentController@update', $document['id'])}}">
	    <div class="form-group row">
	    {{csrf_field()}}
	      <input name="_method" type="hidden" value="PATCH">
	      <label for="document_number" class="col-sm-3 col-form-label">Document Number</label>
	      <div class="col-sm-9">
	        <input type="text" name="document_number" class="form-control" id="document_number" value="{{$document->document_number}}">
	        <input type="hidden" name="document_id" value="{{$document->id}}">
	      </div>
	    </div>
	    <div class="form-group row">
	    	      <label for="customer" class="col-sm-3 col-form-label">Customer</label>
            <div class="col-sm-9">
		        <input type="text" name="customer" class="form-control" id="customer" value="{{$document->part->customer->name}}" disabled>
             </div> 
        </div> 
	    <div class="form-group row">
	    	      <label for="part" class="col-sm-3 col-form-label">Part</label>
            <div class="col-sm-9">
		        <input type="text" name="part_number" class="form-control" id="part" value="{{$document->part->part_number}}" disabled>
		        <input type="hidden" name="part_number" value="{{$document->part->part_number}}">		        
             </div> 
        </div> 
 	    <div class="form-group row">
	    	      <label for="type" class="col-sm-3 col-form-label">Document Type</label>
            <div class="col-sm-9">
             <select name="type_id" class="form-control">
                <option value="">Select Type...</option>
              @foreach($types as $type)
                @if($type['id'] === $document['type']['id'])
		        <option value={{$type->id}} selected>{{$type->name}}</option>
		        @else
		        <option value={{$type->id}}>{{$type->name}}</option>
		        @endif
              @endforeach
              </select>
              </div> 
        </div> 
 	    <div class="form-group row">
	      <label for="type" class="col-sm-3 col-form-label">Process</label>
		    <div class="col-sm-9">
		      <select name="process_id" class="form-control">
		        <option value="">Select Process...</option>
		      @foreach($processes as $process)
		      	@if($process['id'] === $document['process']['id'])
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