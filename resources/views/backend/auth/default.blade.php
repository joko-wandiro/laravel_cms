<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LaraBlog') }}</title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo url('css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php echo url('css/ie10-viewport-bug-workaround.css'); ?>" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?php echo url('css/themes/standard/login.css'); ?>" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	@yield('content')
</body>
</html>
