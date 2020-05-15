<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="..\..\favicon.ico">
    <title>Demo Hituh Laravel CRUD</title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo url('css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo url('css/dkscaffolding.css'); ?>" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php echo url('css/ie10-viewport-bug-workaround.css'); ?>" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
            <?php echo $scaffolding; ?>
        </div>
      </div>
    </div>
    <!-- javascript files -->
    <script type="text/javascript" src="<?php echo url('js/jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo url('js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo url('js/jquery.blockUI.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo url('js/jquery.validate.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo url('js/ie10-viewport-bug-workaround.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo url('js/jquery.dkscaffolding.js'); ?>"></script>
    <script>
    $(document).ready(function () {
        $('.dk-scaffolding').DKScaffolding();
    })
    </script>
  </body>
</html>
