@extends ('layouts.master')

@include ('partials.dataTables')


@section ('content')
        <table id='dataTable' class='display table' cellspacing='0' width='100%'>
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
    
@endsection

@section ('javascript')
    var table = $('#dataTable').DataTable({
        fixedHeader: {
            headerOffset: 55
        },
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
            {data:'{{$dataColumn}}'},
          @endforeach
        ]
    });

    $('#searchInput').keyup(function(){
        table.search($(this).val()).draw();
    });

   $('#optionDropdown').append($('.dataTableButton'));

@endsection