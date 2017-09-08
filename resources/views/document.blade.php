@extends ('layouts.master')

@include ('partials.dataTables')


@section ('content')
<div class="row">
    <div class='col-lg-2 col-md-3 col-sm-4'>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><span id='documentNumber'></span></h4>
                <h6 class="card-subtitle mb-2 text-muted">Type: <span id='type'></span></h6>
                <ul class="list-group">
                    <li class="list-group-item">Customer: <span id='customer'></span></li>
                    <li class="list-group-item">Process: <span id='process'></span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8">
        <table id='dataTableRevision' class='display table' cellspacing='0' width='100%'>
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

    @if (Auth::check()) 
           <a href="{{$createUrl}}"><button type="button" class="btn btn-outline-primary">Add New</button></a>
    @endif

    </div>
</div>
    
@endsection

@section ('javascript')
    var table = $('#dataTableRevision').DataTable({
        pageLength: -1,
        dom: 'Btr',
        buttons: {
            dom: {
                container: {
                    tag: 'div',
                    className: ''
                },
                button: {
                    className: 'dataTableButton dropdown-item'
                 }
            }
        },
        ajax: {
            url: '{{url($url)}}',
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

    table.on( 'xhr', function () {
    var json = table.ajax.json();
    console.log(json);
    var summary = json.summary;
    $('#documentNumber').html(summary.operation);
    $('#type').html(summary.type);
    $('#process').html(summary.process);
    $('#customer').html(summary.customer);
    } );


    $('#searchInput').keyup(function(){
        table.search($(this).val()).draw();
    });

   $('#optionDropdown').append($('.dataTableButton'));

   $('#deleteBtn').click(function(e){
        e.preventDefault();
   });

@endsection