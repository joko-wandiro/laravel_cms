<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Auth;
use MenuBuilder;
use Route;
use App\Models\Settings;

class BackEndController extends Controller
{

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('backend.auth');
        // Get settings value
        $settings = Settings::get_parameters();
        $GLOBALS['settings'] = $settings;
        $this->jsParameters['text_remove'] = trans('main.remove');
        $this->jsParameters['settings'] = $settings;
    }

    /**
     * Actions column
     * 
     * @param  \App\Libraries\Scaffolding\Model $record
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  void
     */
    public function getParameters()
    {
        $records = array(
            array(
                'id' => 1,
                'name' => trans('main.dashboard'),
                'url' => action(config('app.backend_namespace') . 'DashboardController@index'),
                'parent_id' => 0,
                'order' => 1,
            ),
            array(
                'id' => 3,
                'name' => trans('main.tags'),
                'url' => action(config('app.backend_namespace') . 'TagsController@index'),
                'parent_id' => 0,
                'order' => 3,
            ),
            array(
                'id' => 4,
                'name' => trans('main.posts'),
                'url' => action(config('app.backend_namespace') . 'PostsController@index'),
                'parent_id' => 0,
                'order' => 4,
            ),
            array(
                'id' => 5,
                'name' => trans('main.comments'),
                'url' => action(config('app.backend_namespace') . 'CommentsController@index'),
                'parent_id' => 0,
                'order' => 5,
            ),
            array(
                'id' => 6,
                'name' => trans('main.list'),
                'url' => action(config('app.backend_namespace') . 'CategoriesController@index'),
                'parent_id' => 2,
                'order' => 1,
            ),
            array(
                'id' => 7,
                'name' => trans('main.create'),
                'url' => action(config('app.backend_namespace') . 'CategoriesController@index', array('action' => 'create', 'identifier' => 'categories')),
                'parent_id' => 2,
                'order' => 2,
            ),
            array(
                'id' => 8,
                'name' => trans('main.list'),
                'url' => action(config('app.backend_namespace') . 'TagsController@index'),
                'parent_id' => 3,
                'order' => 1,
            ),
            array(
                'id' => 9,
                'name' => trans('main.create'),
                'url' => action(config('app.backend_namespace') . 'TagsController@index', array('action' => 'create', 'identifier' => 'tags')),
                'parent_id' => 3,
                'order' => 2,
            ),
            array(
                'id' => 10,
                'name' => trans('main.list'),
                'url' => action(config('app.backend_namespace') . 'PostsController@index'),
                'parent_id' => 4,
                'order' => 1,
            ),
            array(
                'id' => 11,
                'name' => trans('main.create'),
                'url' => action(config('app.backend_namespace') . 'PostsController@index', array('action' => 'create', 'identifier' => 'posts')),
                'parent_id' => 4,
                'order' => 2,
            ),
            array(
                'id' => 13,
                'name' => trans('main.pages'),
                'url' => action(config('app.backend_namespace') . 'PagesController@index'),
                'parent_id' => 0,
                'order' => 6,
            ),
            array(
                'id' => 14,
                'name' => trans('main.list'),
                'url' => action(config('app.backend_namespace') . 'PagesController@index'),
                'parent_id' => 13,
                'order' => 1,
            ),
            array(
                'id' => 15,
                'name' => trans('main.create'),
                'url' => action(config('app.backend_namespace') . 'PagesController@index', array('action' => 'create', 'identifier' => 'pages')),
                'parent_id' => 13,
                'order' => 2,
            ),
            array(
                'id' => 16,
                'name' => trans('main.users'),
                'url' => action(config('app.backend_namespace') . 'AdminsController@index'),
                'parent_id' => 0,
                'order' => 6,
            ),
            array(
                'id' => 17,
                'name' => trans('main.list'),
                'url' => action(config('app.backend_namespace') . 'AdminsController@index'),
                'parent_id' => 16,
                'order' => 1,
            ),
            array(
                'id' => 18,
                'name' => trans('main.create'),
                'url' => action(config('app.backend_namespace') . 'AdminsController@index', array('action' => 'create', 'identifier' => 'admins')),
                'parent_id' => 16,
                'order' => 2,
            ),
            array(
                'id' => 19,
                'name' => trans('main.medias'),
                'url' => action(config('app.backend_namespace') . 'MediasController@index'),
                'parent_id' => 0,
                'order' => 8,
            ),
            array(
                'id' => 20,
                'name' => trans('main.list'),
                'url' => action(config('app.backend_namespace') . 'MediasController@index'),
                'parent_id' => 19,
                'order' => 1,
            ),
            array(
                'id' => 21,
                'name' => trans('main.create'),
                'url' => action(config('app.backend_namespace') . 'MediasController@index', array('action' => 'create', 'identifier' => 'medias')),
                'parent_id' => 19,
                'order' => 2,
            ),
            array(
                'id' => 22,
                'name' => trans('main.menus'),
                'url' => action(config('app.backend_namespace') . 'MenusController@index'),
                'parent_id' => 0,
                'order' => 7,
            ),
            array(
                'id' => 23,
                'name' => trans('main.settings'),
                'url' => action(config('app.backend_namespace') . 'SettingsController@edit'),
                'parent_id' => 0,
                'order' => 9,
            ),
            array(
                'id' => 24,
                'name' => trans('main.logout'),
                'url' => action(config('app.backend_namespace') . 'AuthController@logout'),
                'parent_id' => 0,
                'order' => 9,
            ),
        );
        $menuAttributes = array('class' => 'sidebar-menu');
        $dropdownListAttributes = array('class' => 's-m-dropdown');
        $dropdownMenuAttributes = array('class' => 's-m-dropdown-menu');
        $MenuBuilder = new MenuBuilder($records);
        $menu = $MenuBuilder->setMenuAttributes($menuAttributes)->setCurrentUrl(request()->getUri())->setDropdownListAttributes($dropdownListAttributes)->setDropdownLinkAttributes(array())->setDropdownMenuAttributes($dropdownMenuAttributes)->setDropdownListElement('')->setDropdownListElementAttributes(array())->get();
        $breadcrumb = $MenuBuilder->getSelectedMenu();
        $parameters = array(
            'menu' => $menu,
            'breadcrumb' => $breadcrumb,
            'jsParameters' => $this->jsParameters,
        );
        return $parameters;
    }

    /**
     * Actions column
     * 
     * @param  \App\Libraries\Scaffolding\Model $record
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  void
     */
    public function actionColumn($record, $Scaffolding)
    {
        $url = $Scaffolding->getActionButtonUrls($record);
        echo '<div class="text-center">
	        <div class="btn-group">
	        	<a href="' . $url['edit'] . '" class="btn btn-primary btn-edit">Edit</a>
	        	<a href="' . $url['delete'] . '" class="btn btn-primary btn-remove">Remove</a>
	        </div>
	    </div>';
    }

}
