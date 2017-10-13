    <nav class="navbar navbar-toggleable-md navbar-light bg-faded fixed-top navbar-DCS">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">

        <ul class="navbar-nav mr-auto">
          @if($title==='Document')
          <li class="nav-item active">
            <a class="nav-link" href="{{ action('DocumentController@index') }}">Documents <span class="sr-only">(current)</span></a>
          </li>
            @else
          <li class="nav-item">
            <a class="nav-link" href="{{ action('DocumentController@index') }}">Documents</a>
          </li>           
           @endif

          @if($title==='Customer')
          <li class="nav-item active">
            <a class="nav-link" href="{{ action('CustomerController@index') }}">Customers <span class="sr-only">(current)</span></a>
          </li>
            @else
          <li class="nav-item">
            <a class="nav-link" href="{{ action('CustomerController@index') }}">Customers</a>
          </li>          
           @endif

          @if($title==='Part')
          <li class="nav-item active">
            <a class="nav-link" href="{{ action('PartController@index') }}">Parts <span class="sr-only"></a>
          </li>
            @else
          <li class="nav-item">
            <a class="nav-link" href="{{ action('PartController@index') }}">Parts</a>
          </li>       
           @endif


          @if($title==='Process')
          <li class="nav-item active">
            <a class="nav-link" href="{{ action('ProcessController@index') }}">Processes <span class="sr-only"></a>
          </li>
            @else
          <li class="nav-item">
            <a class="nav-link" href="{{ action('ProcessController@index') }}">Processes</a>
          </li>      
           @endif

          @if($title==='Type')
          <li class="nav-item active">
            <a class="nav-link" href="{{ action('TypeController@index') }}">Types <span class="sr-only"></a>
          </li>
            @else
          <li class="nav-item">
            <a class="nav-link" href="{{ action('TypeController@index') }}">Types</a>
          </li>     
           @endif

          @if($title==='File')
          <li class="nav-item active">
            <a class="nav-link" href="{{ action('FileController@index') }}">Files <span class="sr-only"></a>
          </li>
            @else
          <li class="nav-item">
            <a class="nav-link" href="{{ action('FileController@index') }}">Files</a>
          </li> 
           @endif

          @if($title==='Collection')
          <li class="nav-item active">
            <a class="nav-link" href="{{ action('CollectionController@index') }}">Collections <span class="sr-only"></a>
          </li>
            @else
          <li class="nav-item">
            <a class="nav-link" href="{{ action('CollectionController@index') }}">Collections</a>
          </li>
           @endif

          @if($title==='ECN')
          <li class="nav-item active">
            <a class="nav-link" href="{{ action('ECNController@index') }}">ECN <span class="sr-only"></a>
          </li>
            @else
          <li class="nav-item">
            <a class="nav-link" href="{{ action('ECNController@index') }}">ECN</a>
          </li>
           @endif


          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Options</a>
            <div class="dropdown-menu" id="optionDropdown" aria-labelledby="dropdown01">
              <!-- Generated from DataTables Buttons.js-->
            </div>
          </li>
        </ul>
        <div class="form-inline my-2 my-lg-0">
          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-search"></i>
            </span>
            <input id="searchInput" type="text" class="form-control" placeholder="Search" aria-label="Search">
             @if (Auth::guest())
            <span class="input-group-addon">
              <a href='{{url('/login')}}'><i class="fal fa-user-circle" aria-hidden="true"></i></a>
            </span>
            @else
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->name }}
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
                </form>
              </div>
            </div>
          @endif
           </div>
        </div>
      </div>
    </nav> 