@extends ('layouts.master')

@section ('content')
<div class='col-md-3'></div>
 	<div class='col-md-6'>
	 <form method="post" action="{{action('RevisionController@update', $revision['id'])}}">
	    {{csrf_field()}}
	      <input name="_method" type="hidden" value="PATCH">
	<div id="files">
	    <div class="form-group row">
	      <label for="file" class="col-sm-3 col-form-label">File</label>
	      <div class="col-sm-9">
	      	<div class="row">
		      	<div class="col-sm-8">
		      	  <input type="file" name="file[]" class="form-control-file" required>
		        </div>
		      	<div id="addFile" class="col-sm-1">
		      	  <i class="fal fa-file-plus"></i>
		        </div>
	    	</div>
	      </div>
	    </div>
 	    <div class="form-group row documentType">
	    	      <label for="type" class="col-sm-3 col-form-label">File Type</label>
            <div class="col-sm-9">
             <select name="file_id[]" class="form-control" required>
                <option value="">Select File Type...</option>
              @foreach($files as $file)
                @if($file['id'] === $revision['document']['type']['id'])
		        <option value={{$file->id}} selected>{{$file->name}}</option>
		        @else
		        <option value={{$file->id}}>{{$file->name}}</option>
		        @endif
              @endforeach
              </select>
              </div> 
        </div>
        <hr> 
	</div>
	    <div class="form-group row">
	      <label for="document_number" class="col-sm-3 col-form-label">Document Number</label>
	      <div class="col-sm-9">
	        <input type="text" name="document_number" class="form-control" id="document_number" value="{{$revision->document->document_number}}" disabled>
	        <input type="hidden" name="document_id" value="{{$revision->document->id}}">
	      </div>
	    </div>
	    <div class="form-group row">
	    	      <label for="customer" class="col-sm-3 col-form-label">Customer</label>
            <div class="col-sm-9">
		        <input type="text" name="customer" class="form-control" id="customer" value="{{$revision->document->part->customer->name}}" disabled>
             </div> 
        </div> 
	    <div class="form-group row">
	    	      <label for="part" class="col-sm-3 col-form-label">Part</label>
            <div class="col-sm-9">
		        <input type="text" name="part_number" class="form-control" id="part" value="{{$revision->document->part->part_number}}" disabled>
		        <input type="hidden" name="part_number" value="{{$revision->document->part->part_number}}">		        
             </div> 
        </div> 
 	    <div class="form-group row">
	    	      <label for="type" class="col-sm-3 col-form-label">Document Type</label>
            <div class="col-sm-9">
             <select name="type_id" class="form-control" disabled>
                <option value="">Select Type...</option>
              @foreach($types as $type)
                @if($type['id'] === $revision['document']['type']['id'])
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
		      <select name="process_id" class="form-control" disabled>
		        <option value="">Select Process...</option>
		      @foreach($processes as $process)
		      	@if($process['id'] === $revision['document']['process']['id'])
		        <option value={{$process->id}} selected>{{$process->name}}</option>
		        @else
		        <option value={{$process->id}}>{{$process->name}}</option>
		        @endif
		      @endforeach
		      </select>
		     </div> 
        </div> 
	    <div class="form-group row">
	    	      <label for="revision" class="col-sm-3 col-form-label">Revision</label>
            <div class="col-sm-9">
		        <input type="text" name="revision" class="form-control" id="revision" value="{{$revision->revision}}" required>
             </div> 
        </div> 
        <div class="form-group row">
	    	      <label for="revision_date" class="col-sm-3 col-form-label">Revision Date</label>
            <div class="col-sm-9">
		        <input type="date" name="revision_date" class="form-control" id="revision_date" value="{{$revision->revision_date}}" required>
             </div> 
        </div>
        <div class="form-group row">
	    	      <label for="description" class="col-sm-3 col-form-label">Description</label>
            <div class="col-sm-9">
	            <textarea class="form-control" name="description" required>{{$revision->description}}</textarea>
             </div> 
        </div> 
        <div class="form-group row">
	    	      <label for="change_description" class="col-sm-3 col-form-label">Change Description</label>
            <div class="col-sm-9">
	            <textarea class="form-control" name="change_description" required>{{$revision->change_description}}</textarea>
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