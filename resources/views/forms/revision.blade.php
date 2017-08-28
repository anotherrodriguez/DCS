@extends ('layouts.master')

@section ('content')
<div class='col-lg-4'>
	 <form method="post" action="{{action('RevisionController@store')}}">
	    <div class="form-group row">
	    {{csrf_field()}}
	      <label for="document_number" class="col-sm-2 col-form-label">Document</label>
	      <div class="col-sm-10">
	        <input type="text" name="document_number" class="form-control" id="document_number" placeholder="Document Number">
	      </div>
	    </div>
	    <div class="form-group row">
	    	      <label for="customer" class="col-sm-2 col-form-label">Customer</label>
            <div class="col-sm-10">
		        <input type="text" name="customer" class="form-control" id="customer" placeholder="Customer">
             </div> 
        </div> 
	    <div class="form-group row">
	    	      <label for="part" class="col-sm-2 col-form-label">Part</label>
            <div class="col-sm-10">
		        <input type="text" name="part" class="form-control" id="part" placeholder="Part Number">
             </div> 
        </div> 
 	    <div class="form-group row">
	    	      <label for="type" class="col-sm-2 col-form-label">Document Type</label>
            <div class="col-sm-10">
		        <input type="text" name="type" class="form-control" id="type" placeholder="type">
             </div> 
        </div> 
	    <div class="form-group row">
	    	      <label for="revision" class="col-sm-2 col-form-label">Revision</label>
            <div class="col-sm-10">
		        <input type="text" name="revision" class="form-control" id="revision" placeholder="Revision">
             </div> 
        </div> 
        <div class="form-group row">
	    	      <label for="revision_date" class="col-sm-2 col-form-label">Revision Date</label>
            <div class="col-sm-10">
		        <input type="date" name="revision_date" class="form-control" id="revision_date" placeholder="Revision">
             </div> 
        </div>
        <div class="form-group row">
	    	      <label for="description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
	            <textarea class="form-control" name="description" placeholder="Description"></textarea>
             </div> 
        </div> 
        <div class="form-group row">
	    	      <label for="change_description" class="col-sm-2 col-form-label">Change Description</label>
            <div class="col-sm-10">
	            <textarea class="form-control" name="change_description" placeholder="Change Description"></textarea>
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