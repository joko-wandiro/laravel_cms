<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use DB;
use Storage;
use App\Models\Posts;
use App\Models\Categories;
use App\Models\Tags;
use App\Models\Comments;

class DashboardController extends BackEndController
{
    /**
     * Build dashboard page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
    	$parameters= $this->getParameters();
    	$Model= new Posts;
    	$parameters['postTotal']= $Model->get()->count();
    	$Model= new Categories;
    	$parameters['categoryTotal']= $Model->get()->count();
    	$Model= new Tags;
    	$parameters['tagTotal']= $Model->get()->count();
    	$Model= new Comments;
    	$parameters['commentTotal']= $Model->get()->count();
    	$Model= new Comments;
    	$parameters['pendingCommentTotal']= $Model->where('status', '=', 0)->get()->count();
        return view('backend.themes.standard.dashboard', $parameters);
    }
    
    /**
     * Sitemap Generator
     *
     * @return Illuminate\View\View
     */
    public function sitemapGenerator()
    {
		$Model= new Posts;
        $Model= $Model->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
        $Model= $Model->where('status', '=', 1)->where('published_at', '<=', date('Y-m-d H:i:s'));
		$Model= $Model->orderBy('published_at', 'DESC');
		$posts= $Model->get();
    	$parameters= array();
    	$parameters['posts']= $posts;
    	$content= view('sitemap', $parameters)->render();
    	Storage::disk('web')->put('sitemap.xml', $content);
    	return "Sitemap has been updated";
    }
}