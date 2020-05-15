<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use Html;
use DB;
use App\Models\PostTags;
use App\Models\Tags;
use App\Models\Pages;
use App\Models\Menus;
use Illuminate\Http\JsonResponse;
use Validator;
use MenuBuilder;

class MenusController extends BackEndController
{

    public $menu = array();

    /**
     * Build posts page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $Model = new Pages;
        $pages = $Model->where('status', '=', 1)->orderBy('id', 'ASC')->get();
        // Get Menus
        $columns = array(
            'menus.page_id',
            'menus.parent_id',
            'menus.order',
            'pages.title',
            'pages.url',
        );
        $Model = new Menus;
        $menus = $Model->join('pages', 'pages.id', '=', 'menus.page_id')
                        ->orderBy('menus.id', 'ASC')->select($columns)->get();
        $records = array();
        $menu_ids = array();
        foreach ($menus as $page) {
            $record = array(
                'id' => $page['page_id'],
                'name' => $page['title'],
                'url' => url($page['url']),
                'parent_id' => $page['parent_id'],
                'order' => $page['order'],
            );
            $records[] = $record;
        }
        $MenuBuilder = new MenuBuilder($records);
        $list_html = $MenuBuilder->getList();
        $parameters = $this->getParameters();
        $parameters['url'] = action(config('app.backend_namespace') . 'MenusController@save');
        $parameters['pages'] = $pages;
        $parameters['menus'] = $menus;
        $parameters['menu_ids'] = $menu_ids;
        $parameters['list_html'] = $list_html;
        $parameters['list_html'] = $list_html;
        return view('backend.themes.standard.menus', $parameters);
    }

    /**
     * Create menu
     *
     * @param array $items
     * @return string
     */
    protected function createMenu($items, $parent_id = 0)
    {
        foreach ($items as $key => $item) {
            $parameters = array();
            $parameters['page_id'] = $item['id'];
            $parameters['parent_id'] = $parent_id;
            $this->menu[] = $parameters;
            (array_key_exists('children', $item) && $item['children'] ) ? $this->createMenu($item['children'], $item['id']) : null;
        }
    }

    public function save()
    {
        $request = request();
        $parameters = $request->all();
        $items = json_decode($parameters['menu'], true);
        $this->createMenu($items);
        $menus = DB::transaction(function ($db) use ($parameters) {
                    // Delete records
                    $Model = new Menus;
                    $result = $Model->where('menu_id', '=', '1')->delete();
                    $menus = array();
                    // Insert records
                    foreach ($this->menu as $key => $item) {
                        $Menu = Menus::create($item);
                        $record = $Menu->toArray();
                        $menus[] = $record['page_id'];
                    }
                    return $menus;
                });
        // Send Response
        if ($request->ajax()) {
            $JsonResponse = new JsonResponse(trans('main.notification.insert.success'), 200);
            $JsonResponse->send();
            exit;
        }
    }

}
