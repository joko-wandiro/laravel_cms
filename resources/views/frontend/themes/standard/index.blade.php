<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <link rel="icon" href="<?php echo url('images/logo.png'); ?>">
        <title>@yield('page_title')</title>
        @yield('meta')
        <link rel="alternate" type="application/rss+xml" title="IdCoderBlog Feed" href="<?php echo action(config('app.frontend_namespace') . 'BlogController@rss'); ?>" />
        <!-- Bootstrap core CSS -->
        <link href="<?php echo url('css/bootstrap.min.css'); ?>" rel="stylesheet">
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="<?php echo url('css/ie10-viewport-bug-workaround.css'); ?>" rel="stylesheet">
        <link href="<?php echo url('css/themes/standard/frontend.css'); ?>" rel="stylesheet">
        <link href="<?php echo url('css/themes/standard/responsive.css'); ?>" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        @include('frontend.themes.standard.analyticstracking')
    </head>
    <body>
        <div id="page-header">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" 
                                data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?php echo url('/'); ?>">
                            <img src="<?php echo url('images/logo.png'); ?>" /></a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <?php echo $menu; ?>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav
        </div>
    </div>
    <div id="page-content" class="container">
        @yield('content')
    </div>
    <div id="page-footer" class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="copyright">&copy; 2017 Joko Wandiro</div>
            </div>
        </div>
    </div>
    <!-- javascript files -->
    <script type="text/javascript" src="<?php echo url('js/jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo url('js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo url('js/ie10-viewport-bug-workaround.js'); ?>"></script>
    <script>
        window.site = <?php echo json_encode($jsParameters); ?>;
    </script>
    @stack('scripts')
</body>
</html>
