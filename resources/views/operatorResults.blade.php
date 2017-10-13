@extends ('layouts.operator')

@include ('partials.dataTables')

@section ('title')
  <div style="width: 90%;">
    <h1>Tech Document Number: {{$collectionIdDisplay}}</h1>
  </div>
  <div>
  <a class="btn btn-primary" href="view" role="button">Back to Search</a>
  </div>
@endsection

@section ('content')

<div class="row">
  <div class="col-md-12">
     <table id='dataTableOperator' class='display table' cellspacing='0' width='100%'>
        <thead id='dataTableHeader'>
            <tr>
             @foreach ($tableColumns as $tableColumn)
                <th>{{$tableColumn}}</th>
            @endforeach
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
 </div>
        <div class="col-md-6 ecnBtn">
          <a class="btn btn-outline-primary btn-lg" href="#" id="ecnLogBtn" role="button">ECN Log</a>
        </div>

        <div class="col-md-6 ecnBtn">
          <a class="btn btn-outline-warning btn-lg float-right" href="#" id="ecnFormBtn" role="button">Submit an ECN</a>
        </div>
</div>

<div class="row" id="ecnTable" style="display:none">
  <div class="col-md-12">
     <table id='ecnDataTable' class='display table' cellspacing='0' width='100%'>
        <thead>
            <tr>
             <th>Operator</th>
             <th>Part Number</th>
             <th>Sequence</th>
             <th>Change Request</th>
             <th>Notes</th>
             <th>Engineer</th>
             <th>Status</th>
             <th>Date</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
 </div>

</div>

<div class="row" id="ecnForm" style="display:none">
  <div class="col-md-2"></div>
  <div class='col-md-8'>
   <form method="post" action="{{action('ECNController@store')}}">
      <div class="form-group row">
      {{csrf_field()}}
      <input type="hidden" name="collectionId" value="{{$collectionId}}">
        <label for="operatorName" class="col-sm-3 col-form-label">Operator Name</label>
        <div class="col-sm-9">
          <input type="text" name="operator" class="form-control form-control-lg" placeholder="First and Last Name" required>
        </div>
      </div>
      <div class="form-group row">
        <label for="partNumber" class="col-sm-3 col-form-label">Part Number</label>
        <div class="col-sm-9">
          <input type="text" name="part_number" id="partNumber" class="form-control form-control-lg" placeholder="Part Number" required>
        </div>
      </div>
      <div class="form-group row">
        <label for="seqNumber" class="col-sm-3 col-form-label">Sequence Number</label>
        <div class="col-sm-2">
          <input type="number" name="sequence_number" class="form-control form-control-lg" id="seqNumber" placeholder="000" required>
        </div>
      </div>
      <div class="form-group row">
        <label for="changeRequest" class="col-sm-3 col-form-label">Change Request</label>
        <div class="col-sm-9">
          @foreach($change_request as $request)
          <div class="form-check form-check-inline">
            <label class="form-check-label">
              <input class="form-check-input requestOptions" type="radio" name="change_request" id="cycleTime" value="{{$request['id']}}" required> {{$request['name']}}
            </label>
          </div>
          @endforeach
        </div>
      </div>

      <div id="newCycleTime" class="form-group row" style="display:none">
        <label for="notes" class="col-sm-3 col-form-label">New Cyle Time</label>
        <label for="setup" class="col-sm-1 col-form-label">Setup</label>
        <div class="col-sm-2">
          <input type="hidden" name="current_setup" id="current_setup">
          <input type="text" name="setup" id="setup" class="form-control form-control-lg" placeholder="">
        </div>
        <label for="production" class="col-form-label">Production</label>
        <div class="col-sm-2">
          <input type="hidden" name="current_production" id="current_production">
          <input type="text" name="production" id="production" class="form-control form-control-lg" placeholder="">
        </div>
      </div>


      <div class="form-group row">
        <label for="notes" class="col-sm-3 col-form-label">Notes</label>
        <div class="col-sm-9">
              <textarea class="form-control form-control-lg" name="notes" placeholder="notes" required></textarea>
        </div>
      </div>
      <div class="form-group row">
        <div class="offset-sm-3 col-sm-9">
          <button type="submit" class="btn btn-primary">submit</button>
        </div>
      </div>
    </form>
</div>
</div>


@endsection

@section ('javascript')
    $('.requestOptions').click(function(){
      if($(this).val()==='1'){
      $('#newCycleTime').show();
    }
    else{
    $('#newCycleTime').hide();
  }
    })



    var table = $('#dataTableOperator').DataTable({
        pageLength: -1,
        dom: 'tr',
        ajax: {
            url: '{{$url}}',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            type: 'GET',
            data: function(d){
                d.test = 'test';
            }
        },
        columns:[
          @foreach ($dataColumns as $dataColumn)
            @if($dataColumn === 'edit' || $dataColumn === 'delete')
                {data:'{{$dataColumn}}', orderable: false},
            @else
                {data:'{{$dataColumn}}'},
            @endif
          @endforeach
        ]
    });

    var encTable = $('#ecnDataTable').DataTable({
        pageLength: -1,
        dom: 'tr',
        ajax: {
            url: '{{action('ECNController@showCollection',$collectionId)}}',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            type: 'GET',
            data: function(d){
                d.collectionId = '{{$collectionId}}';
            }
        },
        columns:[
                {data:'operator'},
                {data:'part_number'},
                {data:'sequence_number'},
                {data:'change_request.name'},
                {data:'notes'},
                {data:'user.name'},
                {data:'status.name'},
                {data:'created_at'}
        ]
    });

    $('#ecnLogBtn').click(function(){
    $('#ecnForm').hide();
        $('#ecnTable').show();

    });  

    $('#ecnFormBtn').click(function(){
    $('#ecnTable').hide();
      collectionId = '{{$collectionId}}';
      $.ajax({
        method: "POST",
        url: "{{action('EpicorController@display')}}",
        headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
        data: { collectionId: collectionId }
      }).done(function(data){
        console.log(data);
        $('#partNumber').val(data.PartNum);
        $('#seqNumber').val(data.OprSeq);
        $('#current_setup').val(data.EstSetHours);
        $('#current_production').val(data.ProdStandard);
        $('#setup').val(data.EstSetHours);
        $('#production').val(data.ProdStandard);
        $('#ecnForm').show();

      });




    
    });



@endsection