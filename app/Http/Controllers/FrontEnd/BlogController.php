<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Auth;
use MenuBuilder;
use Route;
use App\Models\Posts;
use App\Models\Comments;
use App\Models\Categories;
use App\Models\Tags;
use App\Models\Pages;
use App\Models\Settings;
use DB;
use Scaffolding;
use Form;

class BlogController extends FrontEndController {

    /**
     * Home Page
     * 
     * @return Illuminate\View\View
     */
    public function homepage($page = 1) {
        // Get pages
        $Model = new Pages;
        $page = $Model->where('status', '=', 1)->where('id', '=', settings('homepage'))
                        ->orderBy('id', 'ASC')->first();
        $parameters = $this->getParameters();
        $parameters['page'] = $page;
        return view('frontend.themes.standard.page', $parameters)->render();
    }

    /**
     * Category Page
     * 
     * @return Illuminate\View\View
     */
    public function category($category_name, $page = 1) {
        $page = (int) $page;
        $category_name = str_replace("-", " ", $category_name);
        // Get category
        $Model = new Categories;
        $category = $Model->where('name', '=', $category_name)->first();
        // Get posts
        $Model = new Posts;
        $Model = $Model->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
        $Model = $Model->where('status', '=', 1)->where('published_at', '<=', date('Y-m-d H:i:s'))
                ->where('categories.name', '=', $category_name);
        $Model = $Model->orderBy('published_at', 'DESC');
        $status = true;
        while ($status) {
            // Get records
            $posts = $Model->paginate(2, array('posts.id', 'title', 'url', 'categories.name AS category', 'content', 'published_at'), 'page', $page);
            // Page is valid
            $total = $posts->total();
            $lastPage = $posts->lastPage();
            $currentPage = $posts->currentPage();
            if ($currentPage > $lastPage && $total) {
                $page = $lastPage;
            } else {
                $status = false;
            }
        }
        $parameters = $this->getParameters();
        $parameters['category'] = $category;
        $parameters['posts'] = $posts;
        $parameters['baseUrl'] = url('category/' . to_url_component($category_name));
        $parameters['replacementUrl'] = url('category/' . to_url_component($category_name) . '/page');
        return view('frontend.themes.standard.categories', $parameters);
    }

    /**
     * Tag Page
     * 
     * @return Illuminate\View\View
     */
    public function tag($tag_name, $page = 1) {
        $page = (int) $page;
        $tag_name = str_replace("-", " ", $tag_name);
        // Get tag
        $Model = new Tags();
        $tag = $Model->where('name', '=', $tag_name)->first();
        // Get posts
        $Model = new Posts;
        $Model = $Model->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
        $Model = $Model->join('post_tags', 'post_tags.post_id', '=', 'posts.id', 'INNER');
        $Model = $Model->join('tags', 'tags.id', '=', 'post_tags.tag_id', 'INNER');
        $Model = $Model->where('status', '=', 1)->where('published_at', '<=', date('Y-m-d H:i:s'))
                ->where('tags.name', '=', $tag_name);
        $Model = $Model->orderBy('published_at', 'DESC');
        $Model = $Model->groupBy('posts.id');
        $status = true;
        while ($status) {
            // Get records
            $posts = $Model->paginate(5, array('posts.id', 'title', 'url', 'categories.name AS category', 'content', 'published_at'), 'page', $page);
            // Page is valid
            $total = $posts->total();
            $lastPage = $posts->lastPage();
            $currentPage = $posts->currentPage();
            if ($currentPage > $lastPage && $total) {
                $page = $lastPage;
            } else {
                $status = false;
            }
        }
        $parameters = $this->getParameters();
        $parameters['tag'] = $tag;
        $parameters['posts'] = $posts;
        $parameters['baseUrl'] = url('tag/' . to_url_component($tag_name));
        $parameters['replacementUrl'] = url('tag/' . to_url_component($tag_name) . '/page');
        return view('frontend.themes.standard.tags', $parameters);
    }

    /**
     * Search Page
     * 
     * @return Illuminate\View\View
     */
    public function search($search, $page = 1) {
        $page = (int) $page;
        $Model = new Posts;
        $Model = $Model->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
        $Model = $Model->where('status', '=', 1)->where('published_at', '<=', date('Y-m-d H:i:s'))
                ->where('posts.title', 'LIKE', '%' . $search . '%')
                ->orWhere('posts.content', 'LIKE', '%' . $search . '%');
        $Model = $Model->orderBy('published_at', 'DESC');
        $status = true;
        while ($status) {
            // Get records
            $posts = $Model->paginate(5, array('posts.id', 'title', 'url', 'categories.name AS category', 'content', 'published_at'), 'page', $page);
            // Page is valid
            $total = $posts->total();
            $lastPage = $posts->lastPage();
            $currentPage = $posts->currentPage();
            if ($currentPage > $lastPage && $total) {
                $page = $lastPage;
            } else {
                $status = false;
            }
        }
        $parameters = $this->getParameters();
        $parameters['posts'] = $posts;
        $parameters['baseUrl'] = url('search/' . to_url_component($search));
        $parameters['replacementUrl'] = url('search/' . to_url_component($search) . '/page');
        return view('frontend.themes.standard.search', $parameters);
    }

    /**
     * Single Page
     * 
     * @return Illuminate\View\View
     */
    public function page($url) {
        $Model = new Pages;
        $page = $Model->where('status', '=', 1)->where('pages.url', '=', $url)->groupBy('pages.id')->get()->first();
        if (!$page) {
            abort(404);
        }
        $parameters = $this->getParameters();
        $parameters['page'] = $page;
        $parameters['jsParameters'] = array(
//            'url' => action(config('app.frontend_namespace') . 'BlogController@page'),
            'url' => url()->full(),
//            'validationFailure' => trans('main.validation.failure'),
        );
        return view('frontend.themes.standard.page', $parameters);
    }

    /**
     * Single Post
     * 
     * @return Illuminate\View\View
     */
    public function single($page, $url) {
        $httpVerb = request()->getMethod();
        $Scaffolding = new Scaffolding("comments");
        switch ($httpVerb) {
            case "POST":
                // Modify request set default value of status parameter
                $Scaffolding->addHooks("insertModifyRequest", array($this, "modifyRequest"));
                // Hook Filter insertModifyResponse to modify response
                $Scaffolding->addHooks("insertModifyResponse", array($this, "addCommentResponse"));
                $Scaffolding->processInsert();
                break;
        }
        $Model = new Posts;
        $Model = $Model->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
        $Model = $Model->join('post_tags', 'post_tags.post_id', '=', 'posts.id', 'INNER');
        $Model = $Model->join('tags', 'tags.id', '=', 'post_tags.tag_id', 'INNER');
        $post = $Model->where('status', '=', 1)->where('published_at', '<=', date('Y-m-d H:i:s'))
                        ->where('posts.url', '=', $url)->groupBy('posts.id')->get(array('posts.id', 'title', 'categories.name AS category', 'content', DB::raw("GROUP_CONCAT(tags.name ORDER BY tags.name ASC SEPARATOR ',') AS tags"), 'published_at', 'posts.updated_at'))->first();
        if (!$post) {
            abort(404);
        }
        $this->post = $post->toArray();
        $Scaffolding->setTemplate('frontend.themes.standard.comment');
        // Modify form layout
        $Scaffolding->addHooks("modifyLayout", array($this, "modifyCommentFormLayout"));
        // Modify post form group
        $Scaffolding->setFormGroup('post', array($this, 'getFormGroupPost'));
        $commentForm = $Scaffolding->renderCreate();
        $parameters = $this->getParameters();
        $parameters['post'] = $post;
        $parameters['comments'] = $this->getComments($post);
        $parameters['commentForm'] = $commentForm;
        return view('frontend.themes.standard.single', $parameters);
    }

    /**
     * Single Page
     * 
     * @return Illuminate\View\View
     */
    public function getComments($post) {
        $Comments = new Comments;
        return $Comments->where('post', '=', $post['id'])
                        ->where('status', '=', 1)->get();
    }

    /**
     * Modify request set default value of status parameter
     * 
     * @param array $parameters
     * 
     * @return array
     */
    public function modifyRequest($parameters) {
        unset($parameters['status']);
        return $parameters;
    }

    /**
     * Modify comment form layout
     * 
     * @param array $layout
     * 
     * @return array
     */
    public function modifyCommentFormLayout($layout) {
        unset($layout[4]);
        return $layout;
    }

    /**
     * Get form group description
     * 
     * @param array $column
     * @param \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return string
     */
    public function getFormGroupPost($column, $Scaffolding) {
        $column['attributes'] = array(
            'class' => 'form-control dk-number',
        );
        echo Form::hidden($column['name'], $this->post['id'], $column['attributes']);
    }

    /**
     * Add comment response
     * 
     * @return Illuminate\View\View
     */
    public function addCommentResponse($Response) {
        return back()->with('alert_success_comment', trans('main.alert.success.contact'));
    }

    /**
     * RSS
     *
     * @return Illuminate\View\View
     */
    public function rss() {
        $Model = new Posts;
        $Model = $Model->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
        $Model = $Model->where('status', '=', 1)->where('published_at', '<=', date('Y-m-d H:i:s'));
        $Model = $Model->orderBy('published_at', 'DESC');
        $posts = $Model->paginate(10, array('*'), 'page', 1);
        $parameters = array();
        $parameters['posts'] = $posts;
        return response()->view('rss', $parameters)
                        ->header('Content-Type', 'application/xml');
    }

}
