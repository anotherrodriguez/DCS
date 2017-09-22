<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>DCS 2.0</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="stylesheet" type="text/css" href="{{url('css/main.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{url('css/bootstrap.css')}}" />
        <link rel="stylesheet" href="{{url('css/font-awesome-core.css')}}">
        <link rel="stylesheet" href="{{url('css/font-awesome-solid.css')}}">
        <link rel="stylesheet" href="{{url('css/font-awesome-regular.css')}}">
        <link rel="stylesheet" href="{{url('css/font-awesome-light.css')}}">
        <link rel="stylesheet" href="{{url('css/font-awesome-brands.css')}}">
        @yield('css')

        <script src="{{url('js/jquery.js')}}" type="text/javascript"></script>
        <script src="{{url('js/tether.js')}}" type="text/javascript"></script>
        <script src="{{url('js/bootstrap.js')}}" type="text/javascript"></script>
        @yield('js')

        <script defer src="{{url('js/packs/light.js')}}"></script>
        <script defer src="{{url('js/packs/regular.js')}}"></script>
        <script defer src="{{url('js/fontawesome.js')}}"></script>

    </head>

    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        @if (session('status'))
            <div class="containerMessage">
                <div class="alert alert-{{ session('status') }} message">
                    {{ session('message') }}
                </div>
            </div>
      @endif
        @include ('partials.navBar')

    <div class="container-fluid">
        <div class="row">
             @yield('content')
        </div>

    </div><!-- /.container -->

    <footer class="footer">
      <div class="container">
        <p class="text-muted">Copyright  &copy; 2017 Precision Gear Inc. All Rights Reserved.</p>
      </div>
    </footer>


    <script>
        $(document).ready(function(){
            @if (session('status'))
                $( ".containerMessage" ).delay( 4800 ).fadeOut( 300 );
            @endif
            @yield('javascript')
        });
    </script>

    </body>

</html>