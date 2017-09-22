@extends ('layouts.master')

@include ('partials.dataTables')


@section ('content')
<div class="col-md-6">
  <label for="">All Documents</label>
        <table id='dataTableManage' class='display table' cellspacing='0' width='100%'>
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
<div class="col-md-6">
    <label for="">Collections</label>
        <table id='dataTable1' class='display table' cellspacing='0' width='100%'>
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
@endsection

@section ('javascript')
    var options = {
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
            @if($dataColumn === 'addBtn' || $dataColumn === 'delete')
                {data:'{{$dataColumn}}', orderable: false},
            @else
                {data:'{{$dataColumn}}'},
            @endif
          @endforeach
        ]
    };

    var table = $('#dataTable1').DataTable(options);

    options.ajax.url = '{{$urlAll}}';
    var table1 = $('#dataTableManage').DataTable(options);

    $('tbody').on('click','.addBtn',function(e){
      documentId = $(this).attr('data-documentId');
      collectionId = '{{$collectionId}}';
      $.ajax({
        method: "POST",
        url: "{{action('CollectionController@addDocument')}}",
        headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
        data: { collectionId: collectionId, documentId: documentId }
      })
        .done(function() {
          table.ajax.reload();
          table1.ajax.reload();
        });
  });

      $('tbody').on('click','.removeBtn',function(e){
      id = $(this).attr('data-id');
      $.ajax({
        method: "POST",
        url: "{{action('CollectionController@removeDocument')}}",
        headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
        data: { id: id }
      })
        .done(function() {
          table.ajax.reload();
          table1.ajax.reload();
        });
  });



@endsection