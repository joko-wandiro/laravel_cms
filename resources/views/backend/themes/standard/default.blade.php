<?php
$Route = Route::current();
$action = $Route->getAction();
$namespace = '\\' . $action['namespace'] . '\\';
$currentController = str_replace($action['namespace'] . '\\', "", $action['controller']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="Joko Wandiro">
        <link rel="icon" href="<?php echo url('images/logo.png'); ?>">
        <title><?php echo config('app.name'); ?></title>
        <!-- Bootstrap core CSS -->
        <link href="<?php echo url('css/bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo url('css/dkscaffolding.css'); ?>" rel="stylesheet">
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="<?php echo url('css/ie10-viewport-bug-workaround.css'); ?>" rel="stylesheet">
        @stack('styles')
        <!-- Custom styles for this template -->
        <link href="<?php echo url('css/themes/standard/theme.css'); ?>" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 sidebar">
                    <div id="navbar-header-sidebar" class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Notee</a>
                    </div>
                    <div id="sidebar" aria-expanded="false" class="collapse" style="height: 0px;">
                        <?php echo $menu; ?>
                    </div>
                </div>
                <div class="col-sm-9 col-sm-offset-3">
                    <ol class="breadcrumb">
                        <li><a href="<?php echo action(config('app.backend_namespace') . 'DashboardController@index'); ?>">Home</a></li>
                        <?php
                        $breadcrumbLength = count($breadcrumb);
                        $ct = 1;
                        foreach ($breadcrumb as $menu) {
                            if ($breadcrumbLength == $ct) {
                                ?>
                                <li class="active"><?php echo $menu['name']; ?></li>
                                <?php
                            } else {
                                ?>
                                <li><a href="<?php echo $menu['url']; ?>"><?php echo $menu['name']; ?></a></li>
                                <?php
                            }
                            $ct++;
                        }
                        ?>
                    </ol>
                    <div id="site-content">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        <!-- javascript files -->
        <script type="text/javascript" src="<?php echo url('js/jquery.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo url('js/jquery.blockUI.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo url('js/bootstrap.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo url('js/jquery.validate.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo url('js/ie10-viewport-bug-workaround.js'); ?>"></script>
        <script>
            Site = <?php echo json_encode($jsParameters); ?>
        </script>
        <script type="text/javascript" src="<?php echo url('js/themes/standard/sidebar.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo url('js/jquery.dkscaffolding.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo url('js/themes/standard/main.js'); ?>"></script>
        @stack('scripts')
    </body>
</html>
