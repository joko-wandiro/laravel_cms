<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Auth;
use MenuBuilder;
use Route;
use App\Models\Menus;
use App\Models\Settings;

class FrontEndController extends Controller
{

    public function __construct()
    {
        // Get settings value
        $settings = Settings::get_parameters();
        $GLOBALS['settings'] = $settings;
        $this->jsParameters['settings'] = $settings;
    }

    /**
     * Get parameters
     * 
     * @return  array
     */
    public function getParameters()
    {
        // Build Menu
        $columns = array(
            'menus.page_id',
            'menus.parent_id',
            'menus.order',
            'pages.title',
            'pages.url',
        );
        $Model = new Menus;
        $records = $Model->join('pages', 'pages.id', '=', 'menus.page_id')
                        ->orderBy('menus.id', 'ASC')->select($columns)->get();
        $menus = array();
        $ct = 1;
        foreach ($records as $record) {
            $url = $record['url'];
            if (settings('homepage') == $record['id']) {
                $url = '';
            }
            $menus[] = array(
                'id' => $record['page_id'],
                'name' => $record['title'],
                'url' => url($url),
                'parent_id' => $record['parent_id'],
                'order' => $record['order'],
            );
            $ct++;
        }
        $menuAttributes = array('class' => 'nav navbar-nav');
        $dropdownListAttributes = array('class' => 'dropdown');
        $dropdownMenuAttributes = array('class' => 'dropdown-menu');
        $dropdownLinkAttributes = array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown');
        $MenuBuilder = new MenuBuilder($menus);
        $menu = $MenuBuilder->setMenuAttributes($menuAttributes)->setCurrentUrl(request()->getUri())->setDropdownListAttributes($dropdownListAttributes)->setDropdownLinkAttributes($dropdownLinkAttributes)->setDropdownMenuAttributes($dropdownMenuAttributes)->setDropdownListElement('<span class="caret"></span>')->setDropdownListElementAttributes(array())->get();        
        $parameters = array(
            'jsParameters' => array(
                'urls' => array(
                    'site' => url(''),
                    'search' => url('search'),
                ),
            ),
            'author' => 'Joko Wandiro',
            'menu' => $menu,
        );
        return $parameters;
    }

}
