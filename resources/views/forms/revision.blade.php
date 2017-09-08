@extends ('layouts.master')

@section ('content')
<div class='col-lg-4'>
	 <form method="post" action="{{action('RevisionController@store')}}">
	    <div class="form-group row">
	    {{csrf_field()}}
	      <label for="operation" class="col-sm-2 col-form-label">Operation</label>
	      <div class="col-sm-10">
	        <input type="text" name="operation" class="form-control" id="operation" value="{{$document->operation}}" disabled>
	        <input type="hidden" name="document_id" value="{{$document->id}}">	
	      </div>
	    </div>
	    <div class="form-group row">
	    	      <label for="customer" class="col-sm-2 col-form-label">Customer</label>
            <div class="col-sm-10">
		        <input type="text" name="customer" class="form-control" id="customer" value="{{$document->part->customer->name}}" disabled>
             </div> 
        </div> 
	    <div class="form-group row">
	    	      <label for="part" class="col-sm-2 col-form-label">Part</label>
            <div class="col-sm-10">
		        <input type="text" name="part" class="form-control" id="part" value="{{$document->part->part_number}}" disabled>
             </div> 
        </div> 
 	    <div class="form-group row">
	    	      <label for="type" class="col-sm-2 col-form-label">Document Type</label>
            <div class="col-sm-10">
             <select name="type_id" class="form-control" disabled>
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
	      <label for="type" class="col-sm-2 col-form-label">Process</label>
		    <div class="col-sm-10">
		      <select name="process_id" class="form-control" disabled>
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
	    	      <label for="revision" class="col-sm-2 col-form-label">Revision</label>
            <div class="col-sm-10">
		        <input type="text" name="revision" class="form-control" id="revision" placeholder="Revision" required>
             </div> 
        </div> 
        <div class="form-group row">
	    	      <label for="revision_date" class="col-sm-2 col-form-label">Revision Date</label>
            <div class="col-sm-10">
		        <input type="date" name="revision_date" class="form-control" id="revision_date" placeholder="Revision" required>
             </div> 
        </div>
        <div class="form-group row">
	    	      <label for="description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
	            <textarea class="form-control" name="description" placeholder="Description" required></textarea>
             </div> 
        </div> 
        <div class="form-group row">
	    	      <label for="change_description" class="col-sm-2 col-form-label">Change Description</label>
            <div class="col-sm-10">
	            <textarea class="form-control" name="change_description" placeholder="Change Description" required></textarea>
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