@extends ('layouts.master')

@include ('partials.dataTables')


@section ('content')
<div class="col-md-12">
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
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p>Deleting this document will delete all associated revisions.</p>
                    <p>This action cannot be undone.</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <span id="confirmDelete"></span>
                  </div>
                </div>
              </div>
            </div>
    @endif
  </div>
    
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
            @if($dataColumn === 'edit' || $dataColumn === 'delete')
                {data:'{{$dataColumn}}', orderable: false},
            @else
                {data:'{{$dataColumn}}'},
            @endif
          @endforeach
        ]
    });

    $('#searchInput').keyup(function(){
        table.search($(this).val()).draw();
    });

   $('#optionDropdown').append($('.dataTableButton'));

   $('tbody').on('click','.deleteBtn',function(e){
    e.preventDefault();
    $('#confirmDelete').empty();
    $(this).parent().clone().appendTo($('#confirmDelete'));
    $('#exampleModal').modal('toggle');
   });

@endsection