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
    <link href="<?php echo url('js/syntaxhighlighter/styles/shCoreDefault.css'); ?>" rel="stylesheet">
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
				<li class="active">Installation</li>
			</ol>
			<div id="content">
				<div class="page-header">
					<h1>Installation</h1>
				</div>
				<ul>
					<li>Copy files into laravel directory</li>
					<li>
					<p>Add alias for Scaffolding class.</p>
					<p>Open config/app.php file then add the following syntaxes:</p>
					<figure class="highlight">
						<pre class="brush: php;">
						return [
							...
						    'aliases' => [
						    	...
						        'Scaffolding' => 'App\Libraries\Scaffolding\Scaffolding',
						    ],
						];
						</pre>
					</figure>
					</li>
				</ul>
				<div class="page-header">
					<h1>Generate CRUD operation</h1>
				</div>
				<ul>
					<li>
					<p>Add a route with HTTP verb any.</p>
					<p>Open routes/web.php file then add the following syntaxes:</p>
					<figure class="highlight">
						<pre class="brush: php;">
						Route::any('demo', array('as' => 'demo.index', 'uses' => 'DemoController@index'));
						</pre>
					</figure>
					</li>
					<li>
					<p>Create Controller file to handle Request.</p>
					<p>Create app/Http/Controllers/DemoController.php file.</p>
					<figure class="highlight">
						<pre class="brush: php;">
						namespace App\Http\Controllers;

						use App\Http\Controllers\Controller;
						use Scaffolding;

						class DemoController extends Controller
						{

						    /**
						     * Simple Demo
						     *
						     * @return Illuminate\View\View
						     */
						    public function index()
						    {
						    	$Scaffolding= new Scaffolding();
						    	$Scaffolding->setMasterTemplate('demo');
						    	// Define specific table
								$tableCategories= $Scaffolding->getTable("your_table_name");
						        return $Scaffolding->render();
						    }
						}
						</pre>
					</figure>
					</li>
					<li>
					<p>Create master template View.</p>
					<p>Create resources/views/demo.blade.php file.</p>
					<figure class="highlight">
						<pre class="brush: php;">
						&lt;!DOCTYPE html&gt;
						&lt;html lang="en"&gt;
						  &lt;head&gt;
						    &lt;meta charset="utf-8"&gt;
						    &lt;meta http-equiv="X-UA-Compatible" content="IE=edge"&gt;
						    &lt;meta name="viewport" content="width=device-width, initial-scale=1"&gt;
						    &lt;!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags --&gt;
						    &lt;meta name="description" content=""&gt;
						    &lt;meta name="author" content=""&gt;
						    &lt;link rel="icon" href="..\..\favicon.ico"&gt;
						    &lt;title&gt;Demo &lt;/title&gt;
						    &lt;!-- Bootstrap core CSS --&gt;
						    &lt;link href="&lt;?php echo url('css/bootstrap.min.css'); ?&gt;" rel="stylesheet"&gt;
						    &lt;link href="&lt;?php echo url('css/dkscaffolding.css'); ?&gt;" rel="stylesheet"&gt;
						    &lt;!-- IE10 viewport hack for Surface/desktop Windows 8 bug --&gt;
						    &lt;link href="&lt;?php echo url('css/ie10-viewport-bug-workaround.css'); ?&gt;" rel="stylesheet"&gt;
						    &lt;!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --&gt;
						    &lt;!--[if lt IE 9]&gt;
						      &lt;script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"&gt;&lt;/script&gt;
						      &lt;script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"&gt;&lt;/script&gt;
						    &lt;![endif]--&gt;
						  &lt;/head&gt;
						  &lt;body&gt;
						    &lt;div class="container-fluid"&gt;
						      &lt;div class="row"&gt;
						        &lt;div class="col-sm-12"&gt;
									&lt;?php echo $dkscaffolding; ?&gt;
						        &lt;/div&gt;
						      &lt;/div&gt;
						    &lt;/div&gt;
						    &lt;!-- javascript files --&gt;
						    &lt;script type="text/javascript" src="&lt;?php echo url('js/jquery.min.js'); ?&gt;"&gt;&lt;/script&gt;
						    &lt;script type="text/javascript" src="&lt;?php echo url('js/bootstrap.min.js'); ?&gt;"&gt;&lt;/script&gt;
						    &lt;script type="text/javascript" src="&lt;?php echo url('js/jquery.blockUI.js'); ?&gt;"&gt;&lt;/script&gt;
						    &lt;script type="text/javascript" src="&lt;?php echo url('js/jquery.validate.min.js'); ?&gt;"&gt;&lt;/script&gt;
						    &lt;script type="text/javascript" src="&lt;?php echo url('js/ie10-viewport-bug-workaround.js'); ?&gt;"&gt;&lt;/script&gt;
						    &lt;script type="text/javascript" src="&lt;?php echo url('js/jquery.dkscaffolding.js'); ?&gt;"&gt;&lt;/script&gt;
						    &lt;script&gt;
							$(document).ready(function () {
								$('.dk-scaffolding').DKScaffolding();
							})
							&lt;/script&gt;
						  &lt;/body&gt;
						&lt;/html&gt;
						</pre>
					</figure>
					<p>You must add the following syntaxes in your master template View to make Hituh Laravel CRUD works properly.</p>
					<p>Stylesheet files</p>
					<figure class="highlight">
						<pre class="brush: php;">
					    &lt;link href="&lt;?php echo url('css/dkscaffolding.css'); ?&gt;" rel="stylesheet"&gt;
						</pre>
					</figure>
					<p>Javascript files</p>
					<figure class="highlight">
						<pre class="brush: php;">
					    &lt;script type="text/javascript" src="&lt;?php echo url('js/jquery.blockUI.js'); ?&gt;"&gt;&lt;/script&gt;
					    &lt;script type="text/javascript" src="&lt;?php echo url('js/jquery.validate.min.js'); ?&gt;"&gt;&lt;/script&gt;
					    &lt;script type="text/javascript" src="&lt;?php echo url('js/jquery.dkscaffolding.js'); ?&gt;"&gt;&lt;/script&gt;
						</pre>
					</figure>
					<p>$dkScaffolding variable to output CRUD View</p>
					<figure class="highlight">
						<pre class="brush: php;">
						&lt;?php echo $dkscaffolding; ?&gt;
						</pre>
					</figure>
					<p>Call DKScaffolding jQuery plugin</p>
					<figure class="highlight">
						<pre class="brush: php;">
						&lt;script&gt;
						$(document).ready(function () {
							// Call DKScaffolding jQuery plugin
							$('.dk-scaffolding').DKScaffolding();
						})
						&lt;/script&gt;
						</pre>
					</figure>
					</li>
				</ul>
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
	<script type="text/javascript" src="<?php echo url('js/syntaxhighlighter/scripts/shCore.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo url('js/syntaxhighlighter/scripts/shBrushPhp.js'); ?>"></script>
	<script type="text/javascript">SyntaxHighlighter.all();</script>
  </body>
</html>
