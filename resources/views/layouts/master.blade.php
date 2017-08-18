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

        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
        <link rel="stylesheet" href="css/font-awesome-core.css">
        <link rel="stylesheet" href="css/font-awesome-solid.css">
        <link rel="stylesheet" href="css/font-awesome-regular.css">
        <link rel="stylesheet" href="css/font-awesome-light.css">
        <link rel="stylesheet" href="css/font-awesome-brands.css">
        @yield('css')

        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/tether.js" type="text/javascript"></script>
        <script src="js/bootstrap.js" type="text/javascript"></script>
        @yield('js')

        <script defer src="js/packs/light.js"></script>
        <script defer src="js/packs/regular.js"></script>
        <script defer src="js/fontawesome.js"></script>

        <!--<link rel="stylesheet" href="css/main.css"> -->
    </head>

    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        @include ('partials.navBar')

    <div class="container-fluid">
        <div class="col-md-12">
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
            @yield('javascript')
        });
    </script>

    </body>

</html>