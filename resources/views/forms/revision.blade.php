@extends ('layouts.master')

@section ('content')
<div class='col-md-3'></div>
 	<div class='col-md-6'>
	 <form method="post" action="{{action('RevisionController@store')}}" enctype="multipart/form-data">
	    {{csrf_field()}}
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
                <option value={{$file->id}}>{{$file->name}}</option>
              @endforeach
              </select>
              </div> 
        </div>
        <hr> 
	</div>
	    <div class="form-group row">
	      <label for="document_number" class="col-sm-3 col-form-label">Document Number</label>
	      <div class="col-sm-9">
	        <input type="text" name="document_number" class="form-control" id="document_number" value="{{$document->document_number}}" disabled>
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
		        <input type="text" name="part" class="form-control" id="part" value="{{$document->part->part_number}}" disabled>
             </div> 
        </div> 
 	    <div class="form-group row">
	    	      <label for="type" class="col-sm-3 col-form-label">Document Type</label>
            <div class="col-sm-9">
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
	      <label for="type" class="col-sm-3 col-form-label">Process</label>
		    <div class="col-sm-9">
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
	    	      <label for="revision" class="col-sm-3 col-form-label">Revision</label>
            <div class="col-sm-9">
		        <input type="text" name="revision" class="form-control" id="revision" placeholder="Revision" required>
             </div> 
        </div> 
        <div class="form-group row">
	    	      <label for="revision_date" class="col-sm-3 col-form-label">Revision Date</label>
            <div class="col-sm-9">
		        <input type="date" name="revision_date" class="form-control" id="revision_date" placeholder="Revision" required>
             </div> 
        </div>
        <div class="form-group row">
	    	      <label for="description" class="col-sm-3 col-form-label">Description</label>
            <div class="col-sm-9">
	            <textarea class="form-control" name="description" placeholder="Description" required></textarea>
             </div> 
        </div> 
        <div class="form-group row">
	    	      <label for="change_description" class="col-sm-3 col-form-label">Change Description</label>
            <div class="col-sm-9">
	            <textarea class="form-control" name="change_description" placeholder="Change Description" required></textarea>
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

@section('javascript')
 	$('#addFile').click(function(){
 		var documentType = '<div class="form-group row documentType">'+$('.documentType').html()+'</div><hr>';
 		
 		var fileButton ='<div class="col-sm-8"><input type="file" name="file[]" class="form-control-file" required></div>';
 		
 		var removeFile = '<div class="removeFile col-sm-1"><i class="fal fa-file-minus"></i></div>';
 		
 		var fileHtml = '<div class="form-group row"><label for="file" class="col-sm-3 col-form-label">File</label><div class="col-sm-9"><div class="row">'+fileButton+removeFile+'</div></div></div>';
 
 		$('#files').append('<div class="newFile">'+fileHtml+documentType+'</div>');
 	});
 
 	$('#files').on('click', '.removeFile', function(){
 		$(this).closest('.newFile').remove();
 	});
 @endsection