<?php
$Route= Route::current();
$action= $Route->getAction();
$currentController= str_replace($action['namespace'].'\\', "", $action['controller']);
?>
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
    <title>Hituh - Laravel CRUD</title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo url('css/bootstrap.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo url('css/dkscaffolding.css'); ?>" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php echo url('css/ie10-viewport-bug-workaround.css'); ?>" rel="stylesheet">
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
				<a class="navbar-brand" href="#">Laravel CRUD</a>
			</div>
			<div id="sidebar" aria-expanded="false" class="collapse" style="height: 0px;">
				<?php
				$menus= array(
					array(
						'name'=>'DemoController@installation',
						'label'=>'Installation',
					),
					array(
						'name'=>'DemoController@demo_simple',
						'label'=>'Simple',
					),
					array(
						'name'=>'DemoController@demo_set_columns_manually',
						'label'=>'Set columns manually',
					),
					array(
						'name'=>'DemoController@demo_alias_columns',
						'label'=>'Alias columns',
					),
					array(
						'name'=>'DemoController@demo_custom_columns',
						'label'=>'Custom columns',
					),
					array(
						'name'=>'DemoController@demo_formatter_columns',
						'label'=>'Formatter columns',
					),
					array(
						'name'=>'DemoController@demo_set_columns_properties',
						'label'=>'Set columns properties',
					),
					array(
						'name'=>'DemoController@demo_add_custom_action_buttons',
						'label'=>'Add custom action buttons',
					),
					array(
						'name'=>'DemoController@demo_visibility_list_elements',
						'label'=>'Visibility elements in List View',
					),
					array(
						'name'=>'DemoController@demo_custom_search',
						'label'=>'Custom search',
					),
					array(
						'name'=>'DemoController@demo_input_form_filler',
						'label'=>'Input form filler',
						'parameters'=>array(
							'action'=>'create',
							'identifier'=>'posts',
						),
					),
					array(
						'name'=>'DemoController@demo_custom_validation',
						'label'=>'Custom validation',
						'parameters'=>array(
							'action'=>'create',
							'identifier'=>'posts',
						),
					),
					array(
						'name'=>'DemoController@demo_modify_validation_label_error',
						'label'=>'Modify validation label error',
						'parameters'=>array(
							'action'=>'create',
							'identifier'=>'posts',
						),
					),
					array(
						'name'=>'DemoController@demo_custom_form_layout',
						'label'=>'Custom form layout',
						'parameters'=>array(
							'action'=>'create',
							'identifier'=>'posts',
						),
					),
					array(
						'name'=>'DemoController@demo_custom_form_columns',
						'label'=>'Custom form columns',
						'parameters'=>array(
							'action'=>'create',
							'identifier'=>'posts',
						),
					),
					array(
						'name'=>'DemoController@demo_custom_form_input',
						'label'=>'Custom form input',
						'parameters'=>array(
							'action'=>'create',
							'identifier'=>'posts',
						),
					),
					array(
						'name'=>'DemoController@demo_custom_form_input_view',
						'label'=>'Custom form input view',
						'parameters'=>array(
							'action'=>'view',
							'indexes'=>array(
								'posts.id'=>6
							),
							'identifier'=>'posts',
						),
					),
					array(
						'name'=>'DemoController@demo_join_tables',
						'label'=>'Join tables',
					),
					array(
						'name'=>'DemoController@demo_multi_table',
						'label'=>'Multi table',
					),
					array(
						'name'=>'DemoController@demo_multiple_same_table',
						'label'=>'Multiple same table',
					),
					array(
						'name'=>'DemoController@availableHooks',
						'label'=>'Available Hooks Action and Filter',
					),
				);
				?>
				<ul class="sidebar-menu">
					<?php
					foreach( $menus as $menu ){
						$parameters= ( isset($menu['parameters']) ) ? $menu['parameters'] : array();
						$active= ( $currentController == $menu['name'] ) ? ' class="active"' : '';
						?>
					<li<?php echo $active; ?>><a href="<?php echo action($menu['name'], $parameters); ?>"><?php echo $menu['label']; ?></a></li>
						<?php						
					}
					?>
				</ul>
			</div>
        </div>
        <div class="col-sm-9 col-sm-offset-3">
			<ol class="breadcrumb">
				<li><a href="<?php echo action('DemoController@demo_simple'); ?>">Home</a></li>
				<li class="active">Available Hooks Action and Filter</li>
			</ol>
			<div id="content">
				<div class="page-header">
					<h1>Hooks Action</h1>
				</div>
			    <div class="table-responsive">
				    <table class="table table-hover table-condensed">
				        <thead>
				            <tr>
								<th><a href="#">Name</a></th>
								<th><a href="#">Description</a></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><span class="text-highlight">deleteAfterDelete</span></td>
								<td>Run script after delete entry</td>
							</tr>
							<tr>
								<td><span class="text-highlight">updateAfterUpdate</span></td>
								<td>Run script after update entry</td>
							</tr>
							<tr>
								<td><span class="text-highlight">insertAfterInsert</span></td>
								<td>Run script after insert entry</td>
							</tr>
							<tr>
								<td><span class="text-highlight">listFormStart</span></td>
								<td>Run script on start of Form element</td>
							</tr>
							<tr>
								<td><span class="text-highlight">listBeforeTable</span></td>
								<td>Run script before Table element</td>
							</tr>
						</tbody>
				    </table>
		    	</div>
				<div class="page-header">
					<h1>Hooks Filter</h1>
				</div>
			    <div class="table-responsive">
				    <table class="table table-hover table-condensed">
				        <thead>
				            <tr>
								<th><a href="#">Name</a></th>
								<th><a href="#">Description</a></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><span class="text-highlight">modifyLayout</span></td>
								<td>Modify form layout</td>
							</tr>
							<tr>
								<td><span class="text-highlight">modifyColumnsProperties</span></td>
								<td>Modify properties of columns</td>
							</tr>
							<tr>
								<td><span class="text-highlight">modifyLabelFormInputError</span></td>
								<td>Modify label error in validation</td>
							</tr>
							<tr>
								<td><span class="text-highlight">deleteModifyResponse</span></td>
								<td>Modify label error in validation</td>
							</tr>
							<tr>
								<td><span class="text-highlight">modifyValidationRulesJS</span></td>
								<td>Modify javascript validation rules</td>
							</tr>
							<tr>
								<td><span class="text-highlight">updateModifyValidationRules</span></td>
								<td>Modify validation rules for update action</td>
							</tr>
							<tr>
								<td><span class="text-highlight">updateModifyRequest</span></td>
								<td>Modify Request parameters for update action</td>
							</tr>
							<tr>
								<td><span class="text-highlight">updateModifyResponse</span></td>
								<td>Modify Response for update action</td>
							</tr>
							<tr>
								<td><span class="text-highlight">insertModifyValidationRules</span></td>
								<td>Modify validation rules for insert action</td>
							</tr>
							<tr>
								<td><span class="text-highlight">insertModifyRequest</span></td>
								<td>Modify Request parameters for insert action</td>
							</tr>
							<tr>
								<td><span class="text-highlight">insertModifyResponse</span></td>
								<td>Modify Response for insert action</td>
							</tr>
							<tr>
								<td><span class="text-highlight">listModifyColumns</span></td>
								<td>Modify selected columns</td>
							</tr>
							<tr>
								<td><span class="text-highlight">listModifySearch</span></td>
								<td>Modify search functionality such as custom search</td>
							</tr>
						</tbody>
				    </table>
		    	</div>
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
    <script type="text/javascript" src="<?php echo url('js/themes/standard/sidebar.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo url('js/jquery.dkscaffolding.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo url('js/themes/standard/main.js'); ?>"></script>
  </body>
</html>
