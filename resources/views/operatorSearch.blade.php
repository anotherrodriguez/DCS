@extends ('layouts.operator')

@section ('content')
        <div class="row">
          <div class="col-md-2">
          </div>
          <div class="col-md-8">
          <form action="showOperator" method="get" accept-charset="utf-8">
          <div class="input-group">
            <span id="inputAddOnView" class="input-group-addon">
              <i class="fa fa-search"></i>
            </span>
            <input id="searchInputView" name="collection" type="text" class="form-control" placeholder="Enter Tech Document Number" aria-label="Search">
          </div>
       
        </form>
         </div>
     </div>
@endsection
       